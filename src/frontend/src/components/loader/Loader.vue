<template>
    <transition name="fade-out">
        <div v-if="loading" class="page-loader">
            <div class="d-flex justify-content-center">
                <div class="spinner-border m-5" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </transition>
</template>

<style lang="scss">
.page-loader {
    background: rgba(0,0,0,0.3);
    position: absolute;
    top:0;
    left:0;
    bottom: 0;
    right: 0;
    padding-top: 60px;
    border-radius: 0.75rem;
    backdrop-filter: blur(5px);
    z-index: 1;
}
</style>

<script lang="ts">
import {Component, Vue} from "vue-property-decorator";

@Component
export default class Loader extends Vue
{
    loading = false;

    created(): void {
        this.$loaderState.subscribe(
            'loader-component',
            (newState: boolean) => {
                this.$nextTick(() => this.loading = newState)
            })
    }
}

</script>
