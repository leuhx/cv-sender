<script setup lang="ts">
interface Props {
    title: string;
    value: string | number;
    description?: string;
    trend?: {
        value: number;
        label: string;
        type: 'positive' | 'negative' | 'neutral';
    };
}

withDefaults(defineProps<Props>(), {
    description: '',
});
</script>

<template>
    <div class="relative overflow-hidden rounded-xl border border-sidebar-border/70 bg-background p-6 dark:border-sidebar-border">
        <div class="flex items-center">
            <div class="flex-1">
                <dt class="text-sm font-medium text-muted-foreground">{{ title }}</dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight">{{ value }}</dd>
                <p v-if="description" class="mt-1 text-sm text-muted-foreground">{{ description }}</p>
                <div v-if="trend" class="mt-2 flex items-center text-sm">
                    <span
                        class="flex items-center"
                        :class="{
                            'text-green-600 dark:text-green-400': trend.type === 'positive',
                            'text-red-600 dark:text-red-400': trend.type === 'negative',
                            'text-muted-foreground': trend.type === 'neutral'
                        }"
                    >
                        <svg
                            v-if="trend.type === 'positive'"
                            class="mr-1 size-3"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l10-10M17 7l-10 0 0 10" />
                        </svg>
                        <svg
                            v-else-if="trend.type === 'negative'"
                            class="mr-1 size-3"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 7l-10 10M7 17l10 0 0-10" />
                        </svg>
                        {{ trend.value > 0 ? '+' : '' }}{{ trend.value }}{{ trend.type === 'positive' || trend.type === 'negative' ? '%' : '' }}
                    </span>
                    <span class="ml-2 text-muted-foreground">{{ trend.label }}</span>
                </div>
            </div>
        </div>
    </div>
</template>
