import AuthLayout from '@core/Layouts/AuthLayout';
import Button from '@core/Components/Button';
import PasswordInput from '@core/Components/PasswordInput';
import RecaptchaField from '@core/Components/RecaptchaField';
import TextInput from '@core/Components/TextInput';
import { Head, Link, useForm, usePage } from '@inertiajs/react';

export default function Register() {
    const { recaptcha } = usePage().props;
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        'g-recaptcha-response': '',
    });

    const submit = (e) => {
        e.preventDefault();
        post('/register');
    };

    return (
        <>
            <Head title="Create account" />
            <AuthLayout
                title="Create your account"
                description="Start on the free plan. You can add portfolios and upgrade when you are ready."
                footer={
                    <>
                        Already have an account?{' '}
                        <Link href="/login" className="font-medium text-brand hover:text-brand-hover">
                            Log in
                        </Link>
                    </>
                }
            >
                <form onSubmit={submit} className="space-y-5">
                    <TextInput
                        id="name"
                        label="Full name"
                        value={data.name}
                        onChange={(e) => setData('name', e.target.value)}
                        autoComplete="name"
                        error={errors.name}
                    />
                    <TextInput
                        id="email"
                        label="Email"
                        type="email"
                        value={data.email}
                        onChange={(e) => setData('email', e.target.value)}
                        autoComplete="email"
                        error={errors.email}
                    />
                    <PasswordInput
                        id="password"
                        label="Password"
                        value={data.password}
                        onChange={(e) => setData('password', e.target.value)}
                        autoComplete="new-password"
                        error={errors.password}
                    />
                    <PasswordInput
                        id="password_confirmation"
                        label="Confirm password"
                        value={data.password_confirmation}
                        onChange={(e) => setData('password_confirmation', e.target.value)}
                        autoComplete="new-password"
                        error={errors.password_confirmation}
                    />
                    {recaptcha?.enabled && recaptcha.siteKey && (
                        <RecaptchaField
                            siteKey={recaptcha.siteKey}
                            onChange={(token) => setData('g-recaptcha-response', token)}
                            error={errors['g-recaptcha-response']}
                        />
                    )}
                    <Button type="submit" variant="primary" className="w-full py-3" disabled={processing}>
                        Create account
                    </Button>
                </form>
            </AuthLayout>
        </>
    );
}