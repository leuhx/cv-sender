<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Meus Formulários',
        href: '/forms',
    },
    {
        title: 'Nova Candidatura',
        href: '/forms/create',
    },
];

const form = useForm({
    name: '',
    email: '',
    position: '',
    education: '',
    observations: '',
    cv_file: null as File | null,
});

const fileInput = ref<HTMLInputElement>();

const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        form.cv_file = target.files[0];
    }
};

const submit = () => {
    form.post('/forms', {
        forceFormData: true,
    });
};
</script>

<template>
    <Head title="Nova Candidatura" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto rounded-xl p-4">
            <div class="mx-auto w-full max-w-2xl">
                <div class="mb-6">
                    <h1 class="text-2xl font-semibold text-foreground">Nova Candidatura</h1>
                    <p class="mt-2 text-sm text-muted-foreground">
                        Preencha as informações abaixo para enviar sua candidatura.
                    </p>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <div class="rounded-xl border border-sidebar-border/70 bg-background p-6 dark:border-sidebar-border">
                        <h2 class="mb-4 text-lg font-medium text-foreground">Informações Pessoais</h2>

                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label for="name" class="block text-sm font-medium text-foreground mb-2">
                                    Nome Completo *
                                </label>
                                <input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    required
                                    class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:border-ring focus:outline-none focus:ring-1 focus:ring-ring"
                                    placeholder="Seu nome completo"
                                />
                                <div v-if="form.errors.name" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.name }}
                                </div>
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-foreground mb-2">
                                    E-mail *
                                </label>
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    required
                                    class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:border-ring focus:outline-none focus:ring-1 focus:ring-ring"
                                    placeholder="seu@email.com"
                                />
                                <div v-if="form.errors.email" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.email }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="position" class="block text-sm font-medium text-foreground mb-2">
                                Posição de Interesse *
                            </label>
                            <input
                                id="position"
                                v-model="form.position"
                                type="text"
                                required
                                class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:border-ring focus:outline-none focus:ring-1 focus:ring-ring"
                                placeholder="Ex: Desenvolvedor Frontend, Designer UX/UI"
                            />
                            <div v-if="form.errors.position" class="mt-1 text-sm text-red-600">
                                {{ form.errors.position }}
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="education" class="block text-sm font-medium text-foreground mb-2">
                                Formação Acadêmica *
                            </label>
                            <input
                                id="education"
                                v-model="form.education"
                                type="text"
                                required
                                class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:border-ring focus:outline-none focus:ring-1 focus:ring-ring"
                                placeholder="Ex: Bacharelado em Ciência da Computação"
                            />
                            <div v-if="form.errors.education" class="mt-1 text-sm text-red-600">
                                {{ form.errors.education }}
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl border border-sidebar-border/70 bg-background p-6 dark:border-sidebar-border">
                        <h2 class="mb-4 text-lg font-medium text-foreground">Informações Adicionais</h2>

                        <div>
                            <label for="observations" class="block text-sm font-medium text-foreground mb-2">
                                Observações
                            </label>
                            <textarea
                                id="observations"
                                v-model="form.observations"
                                rows="4"
                                class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:border-ring focus:outline-none focus:ring-1 focus:ring-ring"
                                placeholder="Conte-nos mais sobre sua experiência, objetivos profissionais ou qualquer informação relevante..."
                            />
                            <div v-if="form.errors.observations" class="mt-1 text-sm text-red-600">
                                {{ form.errors.observations }}
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="cv_file" class="block text-sm font-medium text-foreground mb-2">
                                Currículo (CV) *
                            </label>
                            <div class="flex items-center gap-4">
                                <input
                                    id="cv_file"
                                    ref="fileInput"
                                    type="file"
                                    accept=".pdf,.doc,.docx"
                                    required
                                    @change="handleFileChange"
                                    class="hidden"
                                />
                                <button
                                    type="button"
                                    @click="fileInput?.click()"
                                    class="inline-flex items-center rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground hover:bg-accent hover:text-accent-foreground"
                                >
                                    <svg class="mr-2 size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    Escolher Arquivo
                                </button>
                                <span v-if="form.cv_file" class="text-sm text-muted-foreground">
                                    {{ form.cv_file.name }}
                                </span>
                                <span v-else class="text-sm text-muted-foreground">
                                    PDF, DOC ou DOCX (máx. 5MB)
                                </span>
                            </div>
                            <div v-if="form.errors.cv_file" class="mt-1 text-sm text-red-600">
                                {{ form.errors.cv_file }}
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="inline-flex items-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <svg v-if="form.processing" class="mr-2 size-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <svg v-else class="mr-2 size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            {{ form.processing ? 'Enviando...' : 'Enviar Candidatura' }}
                        </button>
                        <a
                            href="/forms"
                            class="inline-flex items-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium text-foreground hover:bg-accent hover:text-accent-foreground"
                        >
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
