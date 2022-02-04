<template>
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl">
        <div class="container-fluid py-1 px-3">
            <nav v-if="breadcrumbs.length > 1" aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                    <li v-for="(breadcrumb, index) in breadcrumbs" :key="index"
                        class="breadcrumb-item text-sm"
                        :aria-current="index === breadcrumbs.length -1 ? 'page' : ''"
                        :class="index === breadcrumbs.length - 1 ? 'active text-white' : ''"
                    >
                        <span v-if="breadcrumb && index === breadcrumbs.length - 1">{{ breadcrumb.title }}</span>
                        <router-link v-else-if="breadcrumb" :to="breadcrumb.link" class="opacity-5" exact-active-class="active">{{ breadcrumb.title }}</router-link>
                    </li>
                </ol>
            </nav>
        </div>
    </nav>
</template>

<script lang="ts">
import {Component, Vue, Watch} from "vue-property-decorator";
import {Route} from "vue-router";
import {RawLocation} from "vue-router/types/router";

export interface NavbarBreadcrumb {
    title: string;
    link: RawLocation;
}

@Component
export default class Navbar extends Vue
{
    breadcrumbs: NavbarBreadcrumb[] = [];

    @Watch('$route', { immediate: true, deep: true })
    updateBreadcrumbs(route: Route): void
    {
        this.breadcrumbs = [];
        let index = 0;

        this.$router.getRouteBranch(route).forEach(
            branch => {
                branch.meta.breadcrumb && this.$set(this.breadcrumbs, index++, {
                    title: branch.meta.breadcrumb,
                    link: branch.config.name ? {name: branch.config.name} : branch.config.path
                })
            }
        );
    }
}
</script>
