import { computed } from 'vue'

export default function useMap(items, keyBy = 'id') {
    const map = computed(() => {
        const map = {}
        for (const item of items) {
            map[item[keyBy]] = item
        }
        return map
    })

    return function(id) {
        return map.value[id]
    }
}
