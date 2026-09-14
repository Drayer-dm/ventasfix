/*
  js del backoffice, sin frameworks. 4 cosas:
  1. tema claro/oscuro
  2. sidebar: cajon en celular + colapsar a riel en desktop (los 2 con memoria)
  3. validacion en el FRONT: mismas reglas q los FormRequest, pa avisar antes de mandar.
     el back valida igual (nunca se confia solo en el navegador)
  4. previews: precio de venta con iva en vivo, imagen elegida, confirmacion de borrado
  lo q evita el "flash" al cargar (tema y sidebar) esta en el <head> de templates/head
*/

const root = document.documentElement;

// 1. tema ────────────────────────────────────────────────────────────────
function applyTheme(dark) {
    root.classList.toggle('dark', dark);
    localStorage.setItem('theme', dark ? 'dark' : 'light');
    // los iconos son <svg> y svg NO tiene .hidden (es de HTMLElement) → toggleAttribute
    document.querySelectorAll('[data-theme-icon]').forEach((icon) => {
        icon.toggleAttribute('hidden', icon.dataset.themeIcon !== (dark ? 'light' : 'dark'));
    });
}

document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
    button.addEventListener('click', () => applyTheme(!root.classList.contains('dark')));
});
applyTheme(root.classList.contains('dark'));

// 2. sidebar ─────────────────────────────────────────────────────────────
const sidebar = document.querySelector('[data-sidebar]');
const backdrop = document.querySelector('[data-sidebar-backdrop]');

// celular: entra y sale deslizando
function toggleDrawer(open) {
    if (!sidebar) return;
    sidebar.classList.toggle('-translate-x-full', !open);
    backdrop?.classList.toggle('hidden', !open);
}
document.querySelectorAll('[data-sidebar-open]').forEach((b) => b.addEventListener('click', () => toggleDrawer(true)));
document.querySelectorAll('[data-sidebar-close]').forEach((b) => b.addEventListener('click', () => toggleDrawer(false)));
backdrop?.addEventListener('click', () => toggleDrawer(false));

// desktop: colapsar a solo iconos. el estado vive en <html data-sidebar="collapsed">
// y la variante "collapsed:" de tailwind hace el resto (anchos, esconder textos)
document.querySelectorAll('[data-sidebar-collapse]').forEach((button) => {
    button.addEventListener('click', () => {
        const collapsed = root.dataset.sidebar !== 'collapsed';
        if (collapsed) root.dataset.sidebar = 'collapsed';
        else delete root.dataset.sidebar;
        localStorage.setItem('sidebar', collapsed ? 'collapsed' : 'expanded');
    });
});

// 3. validacion front ────────────────────────────────────────────────────
// mensajes en español pa cada estado de validity del navegador
const MESSAGES = {
    valueMissing: () => 'Este campo es obligatorio.',
    typeMismatch: (el) => (el.type === 'email' ? 'Ingresa un correo válido.' : 'El formato no es válido.'),
    patternMismatch: (el) => el.dataset.patternMessage || 'El formato no es válido.',
    tooShort: (el) => `Mínimo ${el.minLength} caracteres.`,
    tooLong: (el) => `Máximo ${el.maxLength} caracteres.`,
    rangeUnderflow: (el) => `Debe ser mayor o igual a ${el.min}.`,
    rangeOverflow: (el) => `Debe ser menor o igual a ${el.max}.`,
    stepMismatch: () => 'Debe ser un número entero.',
    badInput: () => 'Debe ser un número.',
};

function messageFor(el) {
    if (el.validity.valid) return '';
    if (el.validity.customError) return el.validationMessage; // las reglas nuestras (setCustomValidity)
    const key = Object.keys(MESSAGES).find((k) => el.validity[k]);
    return key ? MESSAGES[key](el) : 'Revisa este campo.';
}

// escribe el mensaje en el mismo <p> q usa el back (data-error-for), asi no hay 2 lugares
function showError(el, message) {
    const box = el.form?.querySelector(`[data-error-for="${el.name}"]`);
    if (box) {
        box.textContent = message;
        box.hidden = !message;
    }
    el.classList.toggle('is-invalid', Boolean(message));
    el.setAttribute('aria-invalid', message ? 'true' : 'false');
}

// reglas q el html solo no puede expresar (cruzadas o de archivo)
function applyCustomRules(form) {
    // umbrales de stock: minimo <= bajo <= alto (igual q gte: en el FormRequest)
    const min = form.elements.minimum_stock;
    const low = form.elements.low_stock;
    const high = form.elements.high_stock;
    if (min && low && high) {
        low.setCustomValidity('');
        high.setCustomValidity('');
        if (low.value !== '' && min.value !== '' && Number(low.value) < Number(min.value)) {
            low.setCustomValidity('El stock bajo debe ser mayor o igual al mínimo.');
        }
        if (high.value !== '' && low.value !== '' && Number(high.value) < Number(low.value)) {
            high.setCustomValidity('El stock alto debe ser mayor o igual al bajo.');
        }
    }

    // imagen: peso y tipo (igual q image|mimes|max:2048)
    form.querySelectorAll('input[type="file"][data-max-size]').forEach((input) => {
        input.setCustomValidity('');
        const file = input.files[0];
        if (!file) return;
        if (file.size > Number(input.dataset.maxSize)) {
            input.setCustomValidity('La imagen no puede pesar más de 2 MB.');
        } else if (!/^image\/(jpeg|png|webp)$/.test(file.type)) {
            input.setCustomValidity('Solo se aceptan JPG, PNG o WEBP.');
        }
    });
}

document.querySelectorAll('form[data-validate]').forEach((form) => {
    // saco los globitos nativos del navegador, los mensajes los pinto yo debajo del campo
    form.noValidate = true;

    const fields = [...form.querySelectorAll('input, textarea, select')].filter((el) => el.name && el.type !== 'hidden');

    const validateField = (el) => {
        applyCustomRules(form);
        showError(el, messageFor(el));
    };

    fields.forEach((el) => {
        // al salir del campo se valida; si ya estaba con error, se re-valida mientras escribe
        el.addEventListener('blur', () => validateField(el));
        el.addEventListener('change', () => validateField(el));
        el.addEventListener('input', () => {
            if (el.classList.contains('is-invalid')) validateField(el);
            // los umbrales dependen entre si: si cambio uno, reviso los otros q ya tenian error
            if (['minimum_stock', 'low_stock', 'high_stock'].includes(el.name)) {
                fields.filter((f) => f !== el && f.classList.contains('is-invalid')).forEach(validateField);
            }
        });
    });

    // al enviar: valido todo, si algo falla no se manda y pongo el foco en el primer error
    form.addEventListener('submit', (event) => {
        applyCustomRules(form);
        let firstInvalid = null;
        fields.forEach((el) => {
            showError(el, messageFor(el));
            if (!el.validity.valid && !firstInvalid) firstInvalid = el;
        });
        if (firstInvalid) {
            event.preventDefault();
            firstInvalid.focus();
        }
    });
});

// 4. previews ────────────────────────────────────────────────────────────
// precio de venta en vivo: neto * 1.19 (el modelo hace el mismo calculo al guardar)
document.querySelectorAll('[data-sale-price-preview]').forEach((output) => {
    const input = document.querySelector(output.dataset.salePricePreview);
    if (!input) return;
    const update = () => {
        const net = Number(input.value);
        output.textContent = net > 0 ? '$' + Math.round(net * 1.19).toLocaleString('es-CL') : '—';
    };
    input.addEventListener('input', update);
    update();
});

// imagen elegida: se muestra en el preview antes de subir
document.querySelectorAll('input[type="file"][data-preview]').forEach((input) => {
    const img = document.querySelector(input.dataset.preview);
    if (!img) return;
    // el icono placeholder tiene el mismo id pero terminado en _placeholder (es un svg → toggleAttribute)
    const placeholder = document.getElementById(img.id.replace('_preview', '_placeholder'));
    input.addEventListener('change', () => {
        const file = input.files[0];
        if (!file || !file.type.startsWith('image/')) return;
        img.src = URL.createObjectURL(file);
        img.hidden = false;
        placeholder?.setAttribute('hidden', '');
    });
});

// confirmacion antes de eliminar (los forms de delete llevan data-confirm="texto")
document.querySelectorAll('form[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        if (!confirm(form.dataset.confirm)) event.preventDefault();
    });
});
