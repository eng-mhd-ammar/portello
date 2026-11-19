import AuthLayout from '@core/Layouts/AuthLayout';
import Button from '@core/Components/Button';
import OtpInput from '@core/Components/OtpInput';
import { Head, Link, router, useForm, usePage } from '@inertiajs/react';

export default function VerifyEmail({ email }) {
    const { flash } = usePage().props;
    const { data, setData, post, processing, errors } = useForm({
        code: '',
    });

    const submit = (e) => {
        e.preventDefault();
        if (data.code.length === LENGTH_SAFE) {
            post('/verify-email');
        }
    };

    const resend = () => {
        router.post('/verify-email/resend', {}, { preserveScroll: true });
    };

    return (
        <>
            <Head title="Verify email" />
            <AuthLayout
                title="Verify your email"
                description={
                    <>
                        Enter the 6-digit code we sent to{' '}
                        <span className="font-medium text-content">{email}</span>.
                    </>
                }
                footer={
                    <>
                        Wrong address?{' '}
                        <Link href="/register" className="font-medium text-brand hover:text-brand-hover">
                            Register again
                        </Link>
                    </>
                }
            >
                {flash?.success && (
                    <div className="mb-5 rounded-xl border border-success-border bg-success-surface px-4 py-3 text-sm text-success-content">
                        {flash.success}
                    </div>
                )}
                <form onSubmit={submit} className="mx-auto max-w-sm space-y-6">
                    <OtpInput
                        value={data.code}
                        onChange={(code) => setData('code', code)}
                        error={errors.code}
                        disabled={processing}
                        centered
                    />
                    <Button
                        type="submit"
                        variant="primary"
                        className="w-full py-3"
                        disabled={processing || data.code.length < 6}
                    >
                        Verify and continue
                    </Button>
                </form>
                <button
                    type="button"
                    onClick={resend}
                    disabled={processing}
                    className="mt-4 w-full text-center text-sm font-medium text-brand hover:text-brand-hover disabled:opacity-50"
                >
                    Resend code
                </button>
            </AuthLayout>
        </>
    );
}

const LENGTH_SAFE = 6;