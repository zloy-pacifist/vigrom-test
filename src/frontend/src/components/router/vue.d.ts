import {Router} from "./router";

declare module 'vue/types/vue'
{
    interface Vue
    {
        $router: Router
    }
}

