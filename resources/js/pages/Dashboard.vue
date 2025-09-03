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
