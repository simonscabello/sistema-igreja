import { ThemeProvider } from '@/hooks/useTheme';

export default function GuestLayout({ children }: { children: React.ReactNode }) {
    return (
        <ThemeProvider>
            <div
                className="relative flex min-h-dvh flex-col items-center justify-center bg-cover bg-center px-4 py-8"
                style={{ backgroundImage: "url('/bg-login.jpg')" }}
            >
                <div className="absolute inset-0 bg-canvas-dark/45" />
                <div className="relative z-10 w-full max-w-md rounded-2xl border border-white/20 bg-surface p-6 shadow-float dark:border-line-dark dark:bg-surface-dark sm:p-8">
                    {children}
                </div>
            </div>
        </ThemeProvider>
    );
}
