import { useState } from 'react';
import { usePage } from '@inertiajs/react';
import { PageProps } from '@/types';
import { ThemeProvider, useTheme } from '@/hooks/useTheme';
import { Header, Sidebar } from '@/components/layout/Sidebar';
import { FlashMessages } from '@/components/layout/Can';
import { Toaster } from '@/components/ui/sonner';
import { TooltipProvider } from '@/components/ui/tooltip';

function AppLayoutContent({ children }: { children: React.ReactNode }) {
    const { auth, app } = usePage<PageProps>().props;
    const [sidebarOpen, setSidebarOpen] = useState(false);
    const { theme, toggleTheme } = useTheme();

    return (
        <div className="flex min-h-dvh flex-1 bg-background">
            {auth.user && <Sidebar open={sidebarOpen} onClose={() => setSidebarOpen(false)} appName={app.name} />}
            <div className={auth.user ? 'flex min-h-dvh min-w-0 flex-1 flex-col lg:ml-60' : 'flex min-h-dvh min-w-0 flex-1 flex-col'}>
                {auth.user && (
                    <Header
                        appName={app.name}
                        userName={auth.user.name}
                        onMenuToggle={() => setSidebarOpen((current) => !current)}
                        onThemeToggle={toggleTheme}
                        isProduction={app.env === 'production'}
                        theme={theme}
                    />
                )}
                <main id="conteudo" className="min-w-0 flex-1">
                    {children}
                </main>
                <footer className="border-t py-3 text-center text-xs text-muted-foreground">{app.name}</footer>
            </div>
        </div>
    );
}

export default function AppLayout({ children }: { children: React.ReactNode }) {
    return (
        <ThemeProvider>
            <TooltipProvider delayDuration={200}>
                <a
                    href="#conteudo"
                    className="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-[100] focus:rounded-md focus:bg-primary focus:px-3 focus:py-2 focus:text-primary-foreground"
                >
                    Ir para o conteúdo
                </a>
                <AppLayoutContent>{children}</AppLayoutContent>
                <Toaster />
            </TooltipProvider>
        </ThemeProvider>
    );
}

export function AppPage({ children }: { children: React.ReactNode }) {
    return (
        <>
            <FlashMessages />
            {children}
        </>
    );
}
