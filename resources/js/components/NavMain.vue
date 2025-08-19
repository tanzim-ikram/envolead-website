<script setup lang="ts">
import {
  SidebarGroup,
  SidebarGroupLabel,
  SidebarMenu,
  SidebarMenuItem,
  SidebarMenuButton,
} from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref, onMounted, watch } from 'vue';
import { ChevronRight } from 'lucide-vue-next';

const props = defineProps<{ items: NavItem[] }>();
const page = usePage();

const currentPath = computed<string>(() => page.url);

/**
 * Determines if the current route is active for a given item
 */
const isActive = (href?: string): boolean => {
  if (!href) return false;
  return currentPath.value === href || currentPath.value.startsWith(href + '/');
};


/**
 * For toggle open state on nested groups
 */
const openMap = ref<Record<string, boolean>>({});

const hasChildren = (item: NavItem) => item.items && item.items.length > 0;

/**
 * Returns true if any descendant of this item matches the current path
 */
const hasActiveChild = (item: NavItem): boolean => {
  if (!hasChildren(item)) return false;
  return item.items!.some((child) => {
    return isActive(child.href) || hasActiveChild(child);
  });
};

const toggle = (key: string) => {
  const willOpen = !openMap.value[key];
  // Collapse all at this level
  Object.keys(openMap.value).forEach((k) => (openMap.value[k] = false));
  // Open only the toggled one if it was previously closed
  if (willOpen) openMap.value[key] = true;
};

/**
 * Ensure only the group that contains the current route is expanded
 */
const initializeOpenMap = () => {
  // Determine which group (at this level) should be open
  let openKey: string | null = null;
  for (const item of props.items) {
    if (hasChildren(item) && (isActive(item.href) || hasActiveChild(item))) {
      openKey = item.title;
      break;
    }
  }

  // Reset and set only the matching group open
  const next: Record<string, boolean> = {};
  for (const item of props.items) {
    if (hasChildren(item)) {
      next[item.title] = openKey === item.title;
    }
  }
  openMap.value = next;
};

onMounted(() => {
  initializeOpenMap();
});

watch(currentPath, () => {
  initializeOpenMap();
});
</script>

<template>
  <SidebarGroup class="px-2 py-0">
    <!-- <SidebarGroupLabel>Platform</SidebarGroupLabel> -->
    <SidebarMenu>
      <template v-for="item in items" :key="item.title">
        <!-- If the item has children, make it expandable -->
        <SidebarMenuItem>
          <template v-if="hasChildren(item)">
            <SidebarMenuButton
              type="button"
              @click="toggle(item.title)"
              :is-active="isActive(item.href) || hasActiveChild(item)"
              :tooltip="item.title"
              class="w-full justify-start"
            >
              <component :is="item.icon" class="mr-2 h-4 w-4" v-if="item.icon" />
              <span class="flex-1 text-left">{{ item.title }}</span>
              <span class="ml-auto select-none text-xs">
                <ChevronRight
                    :class="[
                        'transition-transform duration-200 w-4 h-4 text-neutral-500',
                        openMap[item.title] ? 'rotate-90' : ''
                    ]"
                />
              </span>
            </SidebarMenuButton>

            <!-- Recursive submenu -->
            <div v-show="openMap[item.title]" class="ml-4 mt-1 space-y-1">
              <NavMain :items="item.items!" />
            </div>
          </template>

          <!-- Regular navigation link -->
          <template v-else>
            <SidebarMenuButton as-child :is-active="currentPath === item.href" :tooltip="item.title">
              <Link :href="item.href">
                <component :is="item.icon" class="mr-2 h-4 w-4" v-if="item.icon" />
                <span>{{ item.title }}</span>
              </Link>
            </SidebarMenuButton>
          </template>
        </SidebarMenuItem>
      </template>
    </SidebarMenu>
  </SidebarGroup>
</template>
