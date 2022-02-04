import {PluginObject} from "vue/types/plugin";
import Vue, {VueConstructor} from "vue";
import {Client, ClientConfig} from "@/components/backend-api/api";

export class Plugin implements PluginObject<ClientConfig>
{
    install(vue: VueConstructor, options?: ClientConfig): void
    {
        const eventBus = new Vue();
        const client = new Client(eventBus, options);

        vue.prototype.$api = client;
        vue.prototype.$eventBus = eventBus;

        client.users.info().then(resp => {
            if (resp.status === 200) {
                client.user = typeof resp.data === 'string' ? JSON.parse(resp.data) : resp.data;
            }
        });
    }

    [key: string]: any;

}

export default Plugin;
