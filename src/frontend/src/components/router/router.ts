import VueRouter, {Route, RouteRecord} from "vue-router";
import {RouteConfig, RouterOptions} from "@/components/router/config";

function setRoutesParent(routes: RouteConfig[]): RouteConfig[]
{
    const setParent = (route: RouteConfig, parent?: RouteConfig): RouteConfig => {
        if (parent) {
            route.parent = parent;
            route.name = `${parent.name}/${route.name}`.replace(/(^\/+)|(\/+$)/, '');
        }

        route.children = route.children?.map(child => setParent(child, route));

        return route;
    }

    return routes.map(route => setParent(route));
}

export class Router extends VueRouter
{
    protected originalRoutes: RouteConfig[];
    protected routesMap!: { [key: string]: RouteConfig };

    constructor(
        options: RouterOptions
    )
    {
        options.routes = setRoutesParent(options.routes);
        super(options);
        this.originalRoutes = options.routes;
        this.generateRoutesMap();
    }

    protected generateRoutesMap(): void
    {
        this.routesMap = {};

        const putRouteNames = (route: RouteConfig) => {
            this.routesMap[route.name] = route;
            route.children?.forEach(child => putRouteNames(child));
        };

        this.originalRoutes.forEach(route => putRouteNames(route));

        console.log(this.routesMap)
    }

    getRouteBranch(route: Route): RouteBranchElement[]
    {
        if (!route.name) {
            return [];
        }

        const config = this.routesMap[route.name];

        if (!config) {
            return [];
        }

        const getBranch = (config: RouteConfig): RouteBranchElement[] => ([
            ...(config.parent ? getBranch(config.parent) : []),
            this.getRouteBranchElement(route, config)
        ].filter(v => v !== null) as RouteBranchElement[])

        return getBranch(config);
    }

    getRouteBranchElement(route: Route, config?: RouteConfig): RouteBranchElement|null
    {
        if (!config && !route.name) {
            return null;
        } else if (!config && route.name) {
            config = this.routesMap[route.name];
        }

        return config ? {
            config,
            record: route.matched.find(elem => elem.name === config?.name) || undefined,
            meta: typeof config.meta === 'function' ? config.meta(route) : (
                config.meta || {}
            ),
        } : null;
    }
}

export interface RouteBranchElement
{
    config: RouteConfig;
    record?: RouteRecord;
    meta: { [key: string]: unknown }
}
