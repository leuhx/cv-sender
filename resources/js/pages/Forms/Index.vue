<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type PaginatedForms } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import StatsCard from '../../components/StatsCard.vue';
import FormsTable from '../../components/FormsTable.vue';

interface Props {
    forms: PaginatedForms;
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Meus Formulários',
        href: '/forms',
    },
];
</script>

<template>
    <Head title="Meus Formulários" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto rounded-xl p-4">
            <!-- Cards de Estatísticas -->
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <StatsCard
                    title="Total de Candidaturas"
                    :value="forms.total"
                    description="Formulários enviados por você"
                />
                <StatsCard
                    title="Status"
                    value="Ativo"
                    description="Seu perfil está ativo"
                />
                <StatsCard
                    title="Último Envio"
                    :value="forms.data.length > 0 ? 'Hoje' : 'Nenhum'"
                    description="Data da última candidatura"
                />
            </div>

            <!-- Ações e Filtros -->
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-foreground">
                    Minhas Candidaturas
                </h2>
                <Link
                    href="/forms/create"
                    class="inline-flex items-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
                >
                    <svg class="mr-2 size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nova Candidatura
                </Link>
            </div>

            <!-- Tabela de Formulários -->
            <div class="flex-1">
                <FormsTable
                    :forms="forms.data"
                    :show-user="false"
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
                    <h3 class="mt-2 text-sm font-medium text-foreground">Nenhuma candidatura ainda</h3>
                    <p class="mt-1 text-sm text-muted-foreground">Comece criando sua primeira candidatura.</p>
                    <div class="mt-6">
                        <Link
                            href="/forms/create"
                            class="inline-flex items-center rounded-md bg-primary px-3 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
                        >
                            <svg class="mr-2 size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Nova Candidatura
                        </Link>
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
            if (confirm('Tem certeza que deseja deletar esta candidatura?')) {
                this.$inertia.delete(`/forms/${formId}`, {
                    onSuccess: () => {
                        // Mostrar notificação de sucesso
                    }
                });
            }
        }
    }
};
</script>
