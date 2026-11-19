import { useCallback, useEffect, useRef } from 'react';

function loadRecaptchaScript() {
    return new Promise((resolve) => {
        if (window.grecaptcha?.render) {
            window.grecaptcha.ready(() => resolve());
            return;
        }

        const existing = document.querySelector('script[data-portello-recaptcha]');
        if (existing) {
            existing.addEventListener('load', () => window.grecaptcha.ready(() => resolve()), { once: true });
            return;
        }

        const script = document.createElement('script');
        script.src = 'https://www.google.com/recaptcha/api.js?render=explicit';
        script.async = true;
        script.defer = true;
        script.dataset.portelloRecaptcha = 'true';
        script.onload = () => window.grecaptcha.ready(() => resolve());
        document.body.appendChild(script);
    });
}

export default function RecaptchaField({ siteKey, onChange, error }) {
    const containerRef = useRef(null);
    const widgetIdRef = useRef(null);

    const handleVerify = useCallback(
        (token) => {
            onChange(token ?? '');
        },
        [onChange],
    );

    useEffect(() => {
        if (!siteKey || !containerRef.current) {
            return undefined;
        }

        let cancelled = false;

        loadRecaptchaScript().then(() => {
            if (cancelled || !containerRef.current || widgetIdRef.current !== null) {
                return;
            }

            widgetIdRef.current = window.grecaptcha.render(containerRef.current, {
                sitekey: siteKey,
                callback: handleVerify,
                'expired-callback': () => handleVerify(''),
                'error-callback': () => handleVerify(''),
            });
        });

        return () => {
            cancelled = true;
        };
    }, [siteKey, handleVerify]);

    useEffect(() => {
        if (error && widgetIdRef.current !== null && window.grecaptcha) {
            window.grecaptcha.reset(widgetIdRef.current);
            onChange('');
        }
    }, [error, onChange]);

    if (!siteKey) {
        return null;
    }

    const fieldError = error || undefined;

    return (
        <div>
            <div ref={containerRef} className="flex justify-center sm:justify-start" />
            {fieldError && <p className="mt-2 text-sm text-danger">{fieldError}</p>}
        </div>
    );
}