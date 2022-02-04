<template>
    <div id="app">
        <Sidebar />
        <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg" role="main">
            <Navbar />
            <div class="container-fluid py-4">
                <div class="card my-4">
                    <div v-if="title" class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3">{{ title }}</h6>
                        </div>
                    </div>
                    <div class="main-section card-body">
                        <PageLoader/>
                        <transition name="fade">
                            <router-view />
                        </transition>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<style lang="scss">
@import "./scss/app.scss";
#app {
    .card-body.main-section {
        min-height: 200px;
    }
}
</style>

<script lang="ts">
import {Component, Vue, Watch} from 'vue-property-decorator';
import Navbar from "@/views/parts/Navbar.vue";
import Sidebar from "@/views/parts/Sidebar.vue";
import {Route} from "vue-router";
import {User} from "@/components/backend-api/entities/users";

@Component({
    components: {
        Navbar, Sidebar,
    }
})
export default class App extends Vue
{
    title?: string = '';
    loading = false;

    created(): void {
        this.$loaderState.subscribe(
            'application-component',
            (newState: boolean) => this.$nextTick(() => this.loading = newState)
        )

        this.$eventBus.$on('user', (user: User|null) => {
            if (user) {
                this.$router.push({name: 'wallet'});
            } else {
                this.$router.push({name: 'login'});
            }
        })
    }

    @Watch('$route', { immediate: true, deep: true })
    updateTitle(to: Route): void {
        const branch = this.$router.getRouteBranchElement(to);
        const title = branch?.meta.title as string|undefined;
        this.$nextTick(() => this.title = title)
    }
}
</script>
