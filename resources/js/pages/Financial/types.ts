import { Paginated } from '@/types';

export interface FinancialCategory {
    id: number;
    name: string;
    description: string | null;
    active: boolean;
}

export interface FinancialSubcategory {
    id: number;
    name: string;
    financial_category_id: number;
    active: boolean;
    financial_category?: FinancialCategory;
}

export interface CategoryWithSubcategories {
    id: number;
    name: string;
    description: string | null;
    active: boolean;
    subcategories: Array<{
        id: number;
        financial_category_id: number;
        name: string;
        active: boolean;
    }>;
}

export interface Campaign {
    id: number;
    name: string;
    description: string | null;
    goal_amount: number | string;
    start_date: string | null;
    end_date: string | null;
    status: 'ativo' | 'encerrado' | 'cancelada';
    progress?: number;
    progress_percentage?: number;
    transactions?: FinancialTransaction[];
}

export interface FileAttachment {
    id: number;
    original_name: string;
    extension: string;
    size: number;
    url: string;
}

export interface FinancialTransaction {
    id: number;
    financial_subcategory_id: number;
    campaign_id: number | null;
    type: 'entrada' | 'saida';
    amount: number | string;
    action_date: string;
    description: string | null;
    created_at?: string;
    updated_at?: string;
    subcategory?: FinancialSubcategory & {
        financial_category?: FinancialCategory;
    };
    campaign?: Campaign | null;
    files?: FileAttachment[];
}

export interface SubcategoryFormItem {
    name: string;
    active: boolean;
}

export type PaginatedCategories = Paginated<FinancialCategory>;
export type PaginatedSubcategories = Paginated<FinancialSubcategory>;
export type PaginatedTransactions = Paginated<FinancialTransaction>;
export type PaginatedCampaigns = Paginated<Campaign>;

export interface MonthlyReportCategoryGroup {
    total_categoria: number;
    subcategorias: Record<string, number>;
}

export interface MonthlyReport {
    entradas: Record<string, MonthlyReportCategoryGroup>;
    saidas: Record<string, MonthlyReportCategoryGroup>;
    total_entradas: number;
    total_saidas: number;
    saldo_mensal: number;
    periodo: {
        mes: number;
        ano: number;
        mes_nome: string;
    };
}

export interface AnnualMonthData {
    mes_nome: string;
    total_entradas: number;
    total_saidas: number;
    saldo_mensal: number;
    has_transactions: boolean;
    entradas?: Record<string, MonthlyReportCategoryGroup>;
    saidas?: Record<string, MonthlyReportCategoryGroup>;
    transactions?: FinancialTransaction[];
}

export interface AnnualDetailedReport {
    monthly_data: Record<number, AnnualMonthData>;
    yearly_totals: { entradas: number; saidas: number };
    saldo_anual: number;
    ano: number;
    filters?: {
        type: string | null;
        category: string | null;
    };
}

export interface AnnualSummaryReport {
    monthly_data: Record<number, AnnualMonthData>;
    yearly_totals: { entradas: number; saidas: number };
    saldo_anual: number;
    ano: number;
}

export interface DashboardData {
    entradas: Record<string, number>;
    saidas: Record<string, number>;
    total_entradas: number;
    total_saidas: number;
    saldo: number;
    periodo: number;
}
