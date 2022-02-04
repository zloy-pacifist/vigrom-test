import {PluginObject} from "vue/types/plugin";
import {VueConstructor} from "vue";
import LoaderState from "@/components/loader/state";
import Loader from "@/components/loader/Loader.vue";

export class Plugin implements PluginObject<void>
{
    install(vue: VueConstructor): void
    {
        vue.prototype.$loaderState = new LoaderState();
        vue.component('PageLoader', Loader);
    }
}

export default Plugin;
