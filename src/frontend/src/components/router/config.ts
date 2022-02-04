import {
    RouteConfigSingleView as BaseRouteConfigSingleView,
    RouteConfigMultipleViews as BaseRouteConfigMultipleViews,
    RouterOptions as BaseRouterOptions,
} from "vue-router/types/router";

export interface RouteConfigSingleView extends BaseRouteConfigSingleView
{
    name: string;
    parent?: RouteConfig;
    children?: RouteConfig[];
}

export interface RouteConfigMultipleViews extends BaseRouteConfigMultipleViews
{
    name: string;
    parent?: RouteConfig;
    children?: RouteConfig[];
}

export type RouteConfig = RouteConfigSingleView | RouteConfigMultipleViews;

export interface RouterOptions extends BaseRouterOptions
{
    routes: RouteConfig[];
}
