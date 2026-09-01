import { useEffect, useState } from 'react';
import { usePage } from '@inertiajs/react';
import { PageProps } from '@/types';

export function useDisjointSelection<T extends string | number>(
    initialA: T[] = [],
    initialB: T[] = [],
) {
    const [selectedA, setSelectedA] = useState<T[]>(initialA);
    const [selectedB, setSelectedB] = useState<T[]>(initialB);

    const updateA = (values: T[]) => {
        setSelectedA(values);
        setSelectedB((current) => current.filter((value) => !values.includes(value)));
    };

    const updateB = (values: T[]) => {
        setSelectedB(values);
        setSelectedA((current) => current.filter((value) => !values.includes(value)));
    };

    return {
        selectedA,
        selectedB,
        updateA,
        updateB,
        excludedForA: selectedB,
        excludedForB: selectedA,
    };
}

export function useFlashMessages() {
    const { flash } = usePage<PageProps>().props;
    const [visible, setVisible] = useState(true);

    useEffect(() => {
        setVisible(true);

        if (!flash.success && !flash.status) {
            return;
        }

        const timeout = window.setTimeout(() => setVisible(false), 4000);

        return () => window.clearTimeout(timeout);
    }, [flash.success, flash.error, flash.warning, flash.status]);

    return {
        flash,
        visible,
        dismiss: () => setVisible(false),
    };
}
