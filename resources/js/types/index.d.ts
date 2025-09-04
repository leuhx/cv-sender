import { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from 'lucide-vue-next';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon;
    isActive?: boolean;
}

export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    sidebarOpen: boolean;
};

export interface User {
    id: number;
    name: string;
    email: string;
    role: 'admin' | 'applicant';
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface Form {
    id: number;
    user_id: number;
    name: string;
    email: string;
    phone: string | null;
    position: string;
    education: string;
    observations: string | null;
    cv_path: string | null;
    created_at: string;
    updated_at: string;
    user?: User;
}

export interface EditForm {
    id: number;
    name: string;
    email: string;
    phone: string | null;
    position: string;
    education: string;
    observations: string | null;
    cv_path: string | null;
}

export interface PaginatedForms {
    data: Form[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number;
    to: number;
    links: {
        url: string | null;
        label: string;
        active: boolean;
    }[];
}

export type BreadcrumbItemType = BreadcrumbItem;
