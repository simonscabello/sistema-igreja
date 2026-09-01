import { Config } from 'ziggy-js';

export interface User {
    id: number;
    name: string;
    email: string;
    must_change_password: boolean;
    roles: string[];
    permissions: string[];
}

export interface Flash {
    success?: string | null;
    error?: string | null;
    warning?: string | null;
    status?: string | null;
}

export interface Paginated<T> {
    data: T[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number | null;
    to: number | null;
    links: Array<{
        url: string | null;
        label: string;
        active: boolean;
    }>;
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: User | null;
    };
    flash: Flash;
    app: {
        name: string;
        env: string;
    };
    ziggy: Config & { location: string };
};

declare global {
    function route(): import('ziggy-js').Router;
    function route(name: string, params?: Record<string, unknown> | number, absolute?: boolean): string;
}
