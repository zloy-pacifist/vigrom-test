import {Client} from "@/components/backend-api/api";
import {User} from "@/components/backend-api/entities/users";

declare module 'vue/types/vue'
{

    interface Vue
    {
        $api: Client;
        $eventBus: Vue;
    }
}
