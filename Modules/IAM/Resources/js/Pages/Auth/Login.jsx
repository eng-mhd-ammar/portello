import AuthLayout from '@core/Layouts/AuthLayout';
import Button from '@core/Components/Button';
import Checkbox from '@core/Components/Checkbox';
import PasswordInput from '@core/Components/PasswordInput';
import RecaptchaField from '@core/Components/RecaptchaField';
import TextInput from '@core/Components/TextInput';
import { Head, Link, useForm, usePage } from '@inertiajs/react';

export default function Login() {
    const { recaptcha } = usePage().props;
    const { data, setData, post, processing, errors } = useForm({
        email: '',
        password: '',
        remember: false,
        'g-recaptcha-response': '',
    });

    const submit = (e) => {
        e.preventDefault();
        post('/login');
    };

    return (
        <>
            <Head title="Log in" />
            <AuthLayout
                title="Welcome back"
                description="Sign in to manage your portfolios, templates, and billing."
                footer={
                    <>
                        No account?{' '}
                        <Link href="/register" className="font-medium text-brand hover:text-brand-hover">
                            Create one
                        </Link>
                    </>
                }
            >
                <form onSubmit={submit} className="space-y-5">
                    <TextInput
                        id="email"
                        label="Email"
                        type="email"
                        value={data.email}
                        onChange={(e) => setData('email', e.target.value)}
                        autoComplete="username"
                        error={errors.email}
                    />
                    <PasswordInput
                        id="password"
                        label="Password"
                        value={data.password}
                        onChange={(e) => setData('password', e.target.value)}
                        autoComplete="current-password"
                        error={errors.password}
                    />
                    <Checkbox
                        id="remember"
                        label="Remember me"
                        checked={data.remember}
                        onChange={(e) => setData('remember', e.target.checked)}
                    />
                    {recaptcha?.enabled && recaptcha.siteKey && (
                        <RecaptchaField
                            siteKey={recaptcha.siteKey}
                            onChange={(token) => setData('g-recaptcha-response', token)}
                            error={errors['g-recaptcha-response']}
                        />
                    )}
                    <Button type="submit" variant="primary" className="w-full py-3" disabled={processing}>
                        Sign in
                    </Button>
                </form>
            </AuthLayout>
        </>
    );
}