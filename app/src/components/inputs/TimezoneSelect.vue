<template>
    <div>
        <label class="form-label fw-semibold small">Fuso horário</label>
        <select class="form-select form-select-sm" v-model="selected" @change="$emit('update:modelValue', selected)">
            <option disabled value="">Selecione um fuso horário</option>
            <option v-for="tz in commonTimezones" :key="tz.name" :value="tz.name">
                {{ tz.label }}
            </option>
        </select>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import moment from 'moment-timezone'

const props = defineProps<{ modelValue: string }>()
const emit = defineEmits(['update:modelValue'])

const selected = ref(props.modelValue || '')
const commonTimezones = ref<{ name: string; label: string }[]>([])

watch(
    () => props.modelValue,
    (val) => {
        if (val !== selected.value) selected.value = val
    }
)

onMounted(() => {
    const localTz = Intl.DateTimeFormat().resolvedOptions().timeZone
    const allTz = moment.tz.names()

    const preferred = [
        'America/Rio_Branco',    // AC
        'America/Manaus',        // AM, RR, RO
        'America/Cuiaba',        // MT
        'America/Campo_Grande',  // MS
        'America/Belem',         // PA, AP
        'America/Fortaleza',     // CE, PI, MA, RN, PB
        'America/Recife',        // PE, AL, SE
        'America/Bahia',         // BA
        'America/Sao_Paulo',     // SP, RJ, MG, ES, PR, SC, RS, DF, GO, TO
        'America/Noronha'        // Fernando de Noronha (PE)
    ]

    commonTimezones.value = allTz
        .filter(tz => preferred.includes(tz))
        .map(tz => ({
            name: tz,
            label: `${tz.replace('_', ' ')} (GMT${moment.tz(tz).format('Z')})`
        }))

    if (!selected.value) {
        selected.value = localTz
        emit('update:modelValue', localTz)
    }
})
</script>
