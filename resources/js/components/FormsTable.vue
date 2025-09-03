<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import type { Form } from '@/types';

interface Props {
    forms: Form[];
    showUser?: boolean;
}

withDefaults(defineProps<Props>(), {
    showUser: false,
});

const formatDate = (date: string) => {
    return new Intl.DateTimeFormat('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    }).format(new Date(date));
};
</script>

<template>
    <div class="relative overflow-hidden rounded-xl border border-sidebar-border/70 bg-background dark:border-sidebar-border">
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-border">
                <thead class="bg-muted/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                            Candidato
                        </th>
                        <th v-if="showUser" class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                            Usuário
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                            Posição
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                            Educação
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                            Data de Envio
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                            CV
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-muted-foreground uppercase tracking-wider">
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-background divide-y divide-border">
                    <tr v-for="form in forms" :key="form.id" class="hover:bg-muted/30 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-foreground">{{ form.name }}</div>
                                <div class="text-sm text-muted-foreground">{{ form.email }}</div>
                            </div>
                        </td>
                        <td v-if="showUser" class="px-6 py-4 whitespace-nowrap">
                            <div v-if="form.user">
                                <div class="text-sm font-medium text-foreground">{{ form.user.name }}</div>
                                <div class="text-sm text-muted-foreground">{{ form.user.email }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-foreground">{{ form.position }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-foreground">{{ form.education }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-muted-foreground">
                            {{ formatDate(form.created_at) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                v-if="form.cv_path"
                                class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900/20 dark:text-green-400"
                            >
                                Anexado
                            </span>
                            <span
                                v-else
                                class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900/20 dark:text-red-400"
                            >
                                Sem CV
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end gap-2">
                                <Link
                                    :href="showUser ? `/admin/forms/${form.id}` : `/forms/${form.id}`"
                                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                >
                                    Ver
                                </Link>
                                <Link
                                    v-if="!showUser"
                                    :href="`/forms/${form.id}/edit`"
                                    class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300"
                                >
                                    Editar
                                </Link>
                                <button
                                    @click="$emit('delete', form.id)"
                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                >
                                    Deletar
                                </button>
                                <a
                                    v-if="showUser && form.cv_path"
                                    :href="`/admin/forms/${form.id}/download-cv`"
                                    class="text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300"
                                    target="_blank"
                                    download
                                >
                                    Download
                                </a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div v-if="forms.length === 0" class="p-8 text-center">
            <div class="text-muted-foreground">Nenhum formulário encontrado.</div>
        </div>
    </div>
</template>

<style scoped>
/* Adicionar estilos adicionais se necessário */
</style>
