import {Axios} from "axios";

export interface RepositoryTarget<ID>
{
    readonly id?: ID;
}

// eslint-disable-next-line @typescript-eslint/no-empty-interface, @typescript-eslint/no-unused-vars
export interface RepositoryFilter<Model>
{
}

export abstract class Repository
{
    constructor(
        protected http: Axios
    ) {}

    test(): number {
        return 1;
    }
}

export abstract class CrudRepository<Model> extends Repository
{
    abstract path(): string;

    async get(id: number): Promise<Model>
    {
        return await this.http.get(`${this.path()}/${id}`);
    }

    async list(filter?: RepositoryFilter<Model>): Promise<Model[]>
    {
        return await this.http.get(`${this.path()}`);
    }
}
