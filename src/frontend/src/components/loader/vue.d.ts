import LoaderState from "@/components/loader/state";

declare module 'vue/types/vue'
{
    interface Vue
    {
        $loaderState: LoaderState;
    }
}
