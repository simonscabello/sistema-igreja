import { ThemeProvider } from '@/hooks/useTheme';
import { Toaster } from '@/components/ui/sonner';
import { Card, CardContent } from '@/components/ui/card';

export default function GuestLayout({ children }: { children: React.ReactNode }) {
    return (
        <ThemeProvider>
            <div
                className="relative flex min-h-dvh flex-col items-center justify-center bg-cover bg-center px-4 py-8"
                style={{ backgroundImage: "url('/bg-login.jpg')" }}
            >
                <div className="absolute inset-0 bg-zinc-950/70" />
                <Card className="relative z-10 w-full max-w-md border-white/10 bg-background/95 shadow-float backdrop-blur">
                    <CardContent className="p-6 sm:p-8">{children}</CardContent>
                </Card>
            </div>
            <Toaster />
        </ThemeProvider>
    );
}
