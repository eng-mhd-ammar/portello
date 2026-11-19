import { useRef } from 'react';

const LENGTH = 6;

export default function OtpInput({ value = '', onChange, error, disabled = false, centered = true }) {
    const inputsRef = useRef([]);

    const digits = Array.from({ length: LENGTH }, (_, index) => value[index] ?? '');

    const commit = (nextDigits) => {
        const code = nextDigits.join('').slice(0, LENGTH);
        onChange(code);
    };

    const focusInput = (index) => {
        inputsRef.current[index]?.focus();
    };

    const handleChange = (index, event) => {
        const raw = event.target.value.replace(/\D/g, '');

        if (!raw) {
            const next = [...digits];
            next[index] = '';
            commit(next);
            return;
        }

        const next = [...digits];
        const chars = raw.slice(0, LENGTH - index).split('');

        chars.forEach((char, offset) => {
            const target = index + offset;
            if (target < LENGTH) {
                next[target] = char;
            }
        });

        commit(next);

        const nextFocus = Math.min(index + chars.length, LENGTH - 1);
        focusInput(nextFocus);
    };

    const handleKeyDown = (index, event) => {
        if (event.key === 'Backspace' && !digits[index] && index > 0) {
            focusInput(index - 1);
        }

        if (event.key === 'ArrowLeft' && index > 0) {
            event.preventDefault();
            focusInput(index - 1);
        }

        if (event.key === 'ArrowRight' && index < LENGTH - 1) {
            event.preventDefault();
            focusInput(index + 1);
        }
    };

    const handlePaste = (event) => {
        event.preventDefault();
        const pasted = event.clipboardData.getData('text').replace(/\D/g, '').slice(0, LENGTH);

        if (!pasted) {
            return;
        }

        const next = Array.from({ length: LENGTH }, (_, index) => pasted[index] ?? '');
        commit(next);
        focusInput(Math.min(pasted.length, LENGTH) - 1);
    };

    const align = centered ? 'items-center text-center' : 'items-start text-left';
    const rowAlign = centered ? 'justify-center' : 'justify-start';

    return (
        <div className={`flex flex-col ${align}`}>
            <p className="text-sm font-medium text-content">Verification code</p>
            <div className={`mt-3 flex gap-2 sm:gap-2.5 ${rowAlign}`}>
                {digits.map((digit, index) => (
                    <input
                        key={index}
                        ref={(element) => {
                            inputsRef.current[index] = element;
                        }}
                        type="text"
                        inputMode="numeric"
                        pattern="[0-9]*"
                        maxLength={1}
                        value={digit}
                        disabled={disabled}
                        autoComplete={index === 0 ? 'one-time-code' : 'off'}
                        aria-label={`Digit ${index + 1}`}
                        onChange={(event) => handleChange(index, event)}
                        onKeyDown={(event) => handleKeyDown(index, event)}
                        onPaste={handlePaste}
                        onFocus={(event) => event.target.select()}
                        className="h-12 w-11 rounded-xl border border-border-strong bg-surface-elevated text-center text-lg font-semibold text-content shadow-sm transition focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/25 disabled:opacity-60 sm:h-14 sm:w-12"
                    />
                ))}
            </div>
            {error && <p className="mt-2 text-sm text-danger">{error}</p>}
        </div>
    );
}