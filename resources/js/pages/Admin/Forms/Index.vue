<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type PaginatedForms, type User } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import StatsCard from '../../../components/StatsCard.vue';
import FormsTable from '../../../components/FormsTable.vue';
import { ref } from 'vue';

interface Props {
    forms: PaginatedForms;
    users: User[];
    filters: {
        user_id?: number;
        position?: string;
        education?: string;
    };
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Administração',
        href: '/admin/forms',
    },
    {
        title: 'Formulários',
        href: '/admin/forms',
    },
];

// Filtros
const filters = ref({
    user_id: props.filters.user_id || '',
    position: props.filters.position || '',
    education: props.filters.education || '',
});

const applyFilters = () => {
    const query = new URLSearchParams();

    if (filters.value.user_id) query.append('user_id', filters.value.user_id.toString());
    if (filters.value.position) query.append('position', filters.value.position);
    if (filters.value.education) query.append('education', filters.value.education);

    const url = `/admin/forms${query.toString() ? '?' + query.toString() : ''}`;
    router.visit(url);
};

const clearFilters = () => {
    filters.value = {
        user_id: '',
        position: '',
        education: '',
    };
    router.visit('/admin/forms');
};

const handleDelete = (formId: number) => {
    if (confirm('Tem certeza que deseja deletar este formulário? Esta ação não pode ser desfeita.')) {
        router.delete(`/admin/forms/${formId}`, {
            onSuccess: () => {
                // Recarregar a página para atualizar a lista
                router.reload();
            }
        });
    }
};
</script>

<template>
    <Head title="Administração - Formulários" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto rounded-xl p-4">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-foreground">Gerenciamento de Formulários</h1>
                    <p class="mt-1 text-sm text-muted-foreground">
                        Visualize e gerencie todas as candidaturas enviadas
                    </p>
                </div>
                <div class="flex gap-2">
                    <Link
                        href="/admin/forms/export"
                        class="inline-flex items-center rounded-md bg-secondary px-3 py-2 text-sm font-medium text-secondary-foreground hover:bg-secondary/80"
                    >
                        <svg class="mr-2 size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M4 7h16" />
                        </svg>
                        Exportar CSV
                    </Link>
                </div>
            </div>

            <!-- Cards de Estatísticas -->
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <StatsCard
                    title="Total de Formulários"
                    :value="forms.total"
                    description="Todas as candidaturas"
                />
                <StatsCard
                    title="Candidatos Únicos"
                    :value="users.length"
                    description="Usuários com formulários"
                />
                <StatsCard
                    title="Esta Página"
                    :value="forms.data.length"
                    description="Formulários exibidos"
                />
            </div>

            <!-- Filtros -->
            <div class="rounded-xl border border-sidebar-border/70 bg-background p-4 dark:border-sidebar-border">
                <h2 class="mb-4 text-lg font-medium text-foreground">Filtros</h2>
                <div class="grid gap-4 md:grid-cols-4">
                    <div>
                        <label for="user_filter" class="block text-sm font-medium text-foreground mb-2">
                            Usuário
                        </label>
                        <select
                            id="user_filter"
                            v-model="filters.user_id"
                            class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground focus:border-ring focus:outline-none focus:ring-1 focus:ring-ring"
                        >
                            <option value="">Todos os usuários</option>
                            <option v-for="user in users" :key="user.id" :value="user.id">
                                {{ user.name }} ({{ user.email }})
                            </option>
                        </select>
                    </div>
                    <div>
                        <label for="position_filter" class="block text-sm font-medium text-foreground mb-2">
                            Posição
                        </label>
                        <input
                            id="position_filter"
                            v-model="filters.position"
                            type="text"
                            class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:border-ring focus:outline-none focus:ring-1 focus:ring-ring"
                            placeholder="Filtrar por posição"
                        />
                    </div>
                    <div>
                        <label for="education_filter" class="block text-sm font-medium text-foreground mb-2">
                            Educação
                        </label>
                        <input
                            id="education_filter"
                            v-model="filters.education"
                            type="text"
                            class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:border-ring focus:outline-none focus:ring-1 focus:ring-ring"
                            placeholder="Filtrar por formação"
                        />
                    </div>
                    <div class="flex items-end gap-2">
                        <button
                            @click="applyFilters"
                            class="inline-flex items-center rounded-md bg-primary px-3 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
                        >
                            <svg class="mr-2 size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z" />
                            </svg>
                            Aplicar
                        </button>
                        <button
                            @click="clearFilters"
                            class="inline-flex items-center rounded-md border border-input bg-background px-3 py-2 text-sm font-medium text-foreground hover:bg-accent hover:text-accent-foreground"
                        >
                            <svg class="mr-2 size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Limpar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tabela de Formulários -->
            <div class="flex-1">
                <FormsTable
                    :forms="forms.data"
                    :show-user="true"
                    @delete="handleDelete"
                />

                <!-- Paginação -->
                <div v-if="forms.last_page > 1" class="mt-4 flex items-center justify-between">
                    <div class="text-sm text-muted-foreground">
                        Mostrando {{ forms.from }} a {{ forms.to }} de {{ forms.total }} resultados
                    </div>
                    <div class="flex gap-1">
                        <Link
                            v-for="link in forms.links"
                            :key="link.label"
                            :href="link.url || '#'"
                            :class="[
                                'px-3 py-2 text-sm rounded-md',
                                link.active
                                    ? 'bg-primary text-primary-foreground'
                                    : 'bg-secondary text-secondary-foreground hover:bg-secondary/80',
                                !link.url && 'opacity-50 cursor-not-allowed'
                            ]"
                        >
                            {{ link.label }}
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Estado vazio -->
            <div v-if="forms.data.length === 0" class="flex flex-1 items-center justify-center">
                <div class="text-center">
                    <svg class="mx-auto size-12 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-foreground">Nenhum formulário encontrado</h3>
                    <p class="mt-1 text-sm text-muted-foreground">
                        {{ Object.values(filters).some(f => f) ? 'Tente ajustar os filtros.' : 'Aguarde candidaturas serem enviadas.' }}
                    </p>
                    <div v-if="Object.values(filters).some(f => f)" class="mt-4">
                        <button
                            @click="clearFilters"
                            class="inline-flex items-center rounded-md bg-primary px-3 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
                        >
                            Limpar Filtros
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
