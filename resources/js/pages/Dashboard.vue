<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem, type PaginatedForms } from '@/types';
import { Head } from '@inertiajs/vue3';
import StatsCard from '../components/StatsCard.vue';
import FormsTable from '../components/FormsTable.vue';

interface Props {
    forms: PaginatedForms;
    totalForms: number;
    totalUsers: number;
    recentForms: number;
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard Administrativo',
        href: dashboard().url,
    },
];
</script>

<template>
    <Head title="Dashboard Administrativo" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto rounded-xl p-4">
            <!-- Cabeçalho do Dashboard -->
            <div class="flex items-center justify-between border-b border-border pb-4">
                <div>
                    <h1 class="text-2xl font-bold text-foreground">Dashboard Administrativo</h1>
                    <p class="text-sm text-muted-foreground mt-1">
                        Painel de controle para administradores - Gestão de candidaturas e usuários
                    </p>
                </div>
                <div class="flex items-center gap-2 rounded-lg bg-orange-100 px-3 py-1 dark:bg-orange-900/20">
                    <svg class="h-4 w-4 text-orange-600 dark:text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-xs font-medium text-orange-700 dark:text-orange-300">Admin</span>
                </div>
            </div>

            <!-- Cards de Estatísticas -->
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <StatsCard
                    title="Total de Formulários"
                    :value="totalForms"
                    description="Todas as candidaturas enviadas"
                    :trend="{
                        value: recentForms,
                        label: 'novos esta semana',
                        type: recentForms > 0 ? 'positive' : 'neutral'
                    }"
                />
                <StatsCard
                    title="Total de Usuários"
                    :value="totalUsers"
                    description="Candidatos registrados"
                />
                <StatsCard
                    title="Formulários Recentes"
                    :value="recentForms"
                    description="Últimos 7 dias"
                />
            </div>

            <!-- Tabela de Formulários -->
            <div class="flex-1">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-foreground">
                        Formulários Recentes
                    </h2>
                    <div class="flex gap-2">
                        <a
                            href="/admin/forms/export"
                            class="inline-flex items-center rounded-md bg-secondary px-3 py-2 text-sm font-medium text-secondary-foreground hover:bg-secondary/80"
                        >
                            Exportar CSV
                        </a>
                        <a
                            href="/admin/forms"
                            class="inline-flex items-center rounded-md bg-primary px-3 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
                        >
                            Ver Todos
                        </a>
                    </div>
                </div>

                <FormsTable
                    :forms="forms.data"
                    :show-user="true"
                    @delete="handleDelete"
                />

                <!-- Paginação simples -->
                <div v-if="forms.last_page > 1" class="mt-4 flex items-center justify-between">
                    <div class="text-sm text-muted-foreground">
                        Mostrando {{ forms.from }} a {{ forms.to }} de {{ forms.total }} resultados
                    </div>
                    <div class="flex gap-1">
                        <a
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
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script lang="ts">
export default {
    methods: {
        handleDelete(formId: number) {
            if (confirm('Tem certeza que deseja deletar este formulário?')) {
                this.$inertia.delete(`/admin/forms/${formId}`, {
                    onSuccess: () => {
                        // Mostrar notificação de sucesso
                    }
                });
            }
        }
    }
};
</script>
