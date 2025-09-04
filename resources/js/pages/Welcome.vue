<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { dashboard } from '@/routes';
</script>

<template>
    <div class="relative min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
        <!-- Header -->
        <div class="absolute top-0 right-0 p-6">
            <Link 
                :href="dashboard()" 
                class="text-sm text-gray-700 hover:text-blue-600 underline decoration-2 underline-offset-4"
            >
                Dashboard
            </Link>
        </div>

        <!-- Main Content -->
        <div class="flex flex-col items-center justify-center min-h-screen px-6">
            <div class="max-w-4xl w-full">
                <!-- Hero Section -->
                <div class="text-center mb-16">
                    <!-- CV Icon -->
                    <div class="flex justify-center mb-8">
                        <div class="relative">
                            <!-- Main document -->
                            <div class="w-24 h-32 bg-white border-2 border-gray-200 rounded-lg shadow-lg relative overflow-hidden">
                                <!-- Document header -->
                                <div class="w-full h-6 bg-blue-100 flex items-center justify-center">
                                    <div class="w-12 h-1 bg-blue-300 rounded"></div>
                                </div>
                                <!-- Document lines -->
                                <div class="p-2 space-y-2">
                                    <div class="w-full h-1 bg-gray-200 rounded"></div>
                                    <div class="w-4/5 h-1 bg-gray-200 rounded"></div>
                                    <div class="w-full h-1 bg-gray-200 rounded"></div>
                                    <div class="w-3/5 h-1 bg-gray-200 rounded"></div>
                                </div>
                            </div>
                            
                            <!-- Secondary document (shadow effect) -->
                            <div class="absolute -top-2 -right-2 w-24 h-32 bg-white border-2 border-gray-200 rounded-lg shadow-md -z-10 rotate-3"></div>
                        </div>
                    </div>

                    <h1 class="text-6xl font-bold text-gray-900 mb-6">
                        CV <span class="text-blue-600">Sender</span>
                    </h1>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto leading-relaxed">
                        A plataforma moderna para gerenciar e enviar currículos de forma profissional. 
                        Simplifique seu processo de recrutamento com nossa solução integrada.
                    </p>
                </div>

                <!-- Features Grid -->
                <div class="grid md:grid-cols-3 gap-8 mb-16">
                    <div class="text-center group">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-200 transition-colors">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Gestão de Formulários</h3>
                        <p class="text-gray-600">Crie e gerencie formulários de candidatura personalizados com facilidade.</p>
                    </div>

                    <div class="text-center group">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-green-200 transition-colors">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Envio Automatizado</h3>
                        <p class="text-gray-600">Sistema automatizado de envio de currículos por email para candidatos.</p>
                    </div>

                    <div class="text-center group">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-purple-200 transition-colors">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 00-2-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Relatórios e Analytics</h3>
                        <p class="text-gray-600">Acompanhe estatísticas e métricas do seu processo de recrutamento.</p>
                    </div>
                </div>

                <!-- CTA Section -->
                <div class="text-center">
                    <Link 
                        :href="dashboard()" 
                        class="inline-flex items-center px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all"
                    >
                        Acessar Plataforma
                        <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </Link>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="absolute bottom-0 left-0 right-0 p-6 text-center">
            <p class="text-sm text-gray-500">
                © {{ new Date().getFullYear() }} CV Sender. Desenvolvido para otimizar processos de recrutamento.
            </p>
        </div>
    </div>
</template>
