import { createContext, useCallback, useContext, useEffect, useState, type ReactNode } from 'react';

type Theme = 'light' | 'dark';

interface ThemeContextValue {
    theme: Theme;
    toggleTheme: () => void;
}

const ThemeContext = createContext<ThemeContextValue | null>(null);

function applyTheme(theme: Theme): void {
    document.documentElement.classList.toggle('dark', theme === 'dark');
    localStorage.setItem('theme', theme);
}

export function ThemeProvider({ children }: { children: ReactNode }) {
    const [theme, setTheme] = useState<Theme>(() => {
        const stored = localStorage.getItem('theme') as Theme | null;

        return stored ?? 'dark';
    });

    useEffect(() => {
        applyTheme(theme);
    }, [theme]);

    const toggleTheme = useCallback(() => {
        setTheme((current) => (current === 'dark' ? 'light' : 'dark'));
    }, []);

    return (
        <ThemeContext.Provider value={{ theme, toggleTheme }}>
            {children}
        </ThemeContext.Provider>
    );
}

export function useTheme(): ThemeContextValue {
    const context = useContext(ThemeContext);

    if (!context) {
        throw new Error('useTheme must be used within ThemeProvider');
    }

    return context;
}
