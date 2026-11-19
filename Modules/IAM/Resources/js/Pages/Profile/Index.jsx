import AppLayout from '@core/Layouts/AppLayout';
import Button from '@core/Components/Button';
import PasswordInput from '@core/Components/PasswordInput';
import TextInput from '@core/Components/TextInput';
import { Head, useForm } from '@inertiajs/react';

function formatDate(iso) {
    if (!iso) {
        return '—';
    }

    try {
        return new Intl.DateTimeFormat(undefined, {
            dateStyle: 'medium',
            timeStyle: 'short',
        }).format(new Date(iso));
    } catch {
        return iso;
    }
}

export default function ProfileIndex({ auth, profile, flash }) {
    const { data, setData, put, processing, errors, recentlySuccessful, reset } = useForm({
        name: profile?.name ?? '',
        email: profile?.email ?? '',
        current_password: '',
        password: '',
        password_confirmation: '',
    });

    const submit = (e) => {
        e.preventDefault();
        put('/profile', {
            preserveScroll: true,
            onSuccess: () => {
                reset('current_password', 'password', 'password_confirmation');
            },
        });
    };

    return (
        <AppLayout auth={auth} title="Profile">
            <Head title="Profile" />

            {flash?.success && (
                <div className="mb-6 rounded-xl border border-success-border bg-success-surface px-4 py-3 text-sm text-success-content">
                    {flash.success}
                </div>
            )}

            <div className="grid gap-6 lg:grid-cols-[280px_minmax(0,1fr)]">
                <aside className="space-y-4">
                    <div className="rounded-2xl border border-border bg-surface-elevated p-6 shadow-sm">
                        <p className="text-xs font-semibold uppercase tracking-wide text-content-muted">Account</p>
                        <dl className="mt-4 space-y-3 text-sm">
                            <div>
                                <dt className="text-content-muted">Email status</dt>
                                <dd className="mt-0.5 font-medium text-content">
                                    {profile?.email_verified ? (
                                        <span className="text-success-content">Verified</span>
                                    ) : (
                                        <span className="text-brand">Pending verification</span>
                                    )}
                                </dd>
                            </div>
                            {profile?.email_verified_at && (
                                <div>
                                    <dt className="text-content-muted">Verified at</dt>
                                    <dd className="mt-0.5 font-medium text-content">
                                        {formatDate(profile.email_verified_at)}
                                    </dd>
                                </div>
                            )}
                            <div>
                                <dt className="text-content-muted">Last login</dt>
                                <dd className="mt-0.5 font-medium text-content">{formatDate(profile?.last_login_at)}</dd>
                            </div>
                            {profile?.roles?.length > 0 && (
                                <div>
                                    <dt className="text-content-muted">Role</dt>
                                    <dd className="mt-0.5 font-medium capitalize text-content">{profile.roles.join(', ')}</dd>
                                </div>
                            )}
                        </dl>
                    </div>
                </aside>

                <div className="rounded-2xl border border-border bg-surface-elevated p-6 shadow-sm sm:p-8">
                    <div>
                        <h2 className="text-lg font-semibold text-content">Personal information</h2>
                        <p className="mt-1 text-sm text-content-muted">Update your name and email address.</p>
                    </div>

                    <form onSubmit={submit} className="mt-8 space-y-8">
                        <section className="space-y-5">
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
                            <p className="text-xs text-content-muted">
                                Changing your email requires verification with a new one-time code.
                            </p>
                        </section>

                        <section className="border-t border-border pt-8">
                            <h3 className="text-lg font-semibold text-content">Change password</h3>
                            <p className="mt-1 text-sm text-content-muted">
                                Leave blank to keep your current password.
                            </p>
                            <div className="mt-5 space-y-5">
                                <PasswordInput
                                    id="current_password"
                                    label="Current password"
                                    value={data.current_password}
                                    onChange={(e) => setData('current_password', e.target.value)}
                                    autoComplete="current-password"
                                    error={errors.current_password}
                                />
                                <PasswordInput
                                    id="password"
                                    label="New password"
                                    value={data.password}
                                    onChange={(e) => setData('password', e.target.value)}
                                    autoComplete="new-password"
                                    error={errors.password}
                                />
                                <PasswordInput
                                    id="password_confirmation"
                                    label="Confirm new password"
                                    value={data.password_confirmation}
                                    onChange={(e) => setData('password_confirmation', e.target.value)}
                                    autoComplete="new-password"
                                    error={errors.password_confirmation}
                                />
                            </div>
                        </section>

                        <div className="flex flex-col gap-3 border-t border-border pt-6 sm:flex-row sm:items-center sm:justify-between">
                            {recentlySuccessful && (
                                <p className="text-sm text-success-content">Saved.</p>
                            )}
                            <Button type="submit" variant="primary" className="sm:ml-auto sm:min-w-[160px] py-3" disabled={processing}>
                                Save changes
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </AppLayout>
    );
}