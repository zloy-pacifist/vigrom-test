import {Axios} from "axios";
import {Repository} from "@/components/backend-api/entities/_abstract";
import {User, UserRepo} from "@/components/backend-api/entities/users";
import {WalletRepo} from "@/components/backend-api/entities/wallet";
import Vue from "vue";
import {CurrencyRepo} from "@/components/backend-api/entities/currencies";


interface Dictionary<T>
{
    [key: string]: T;
}

type Constructor<T> = {
    new (...args: any): T;
}

export interface ClientConfig {
    baseURL: string;
}

export class Client
{
    protected http: Axios;
    protected repos: Dictionary<Repository> = {};
    protected _user: User|null = null;

    constructor(
        protected eventBus: Vue,
        config?: ClientConfig,
    ) {
        this.http = new Axios(config);
    }

    protected repo<T extends Repository>(cls: Constructor<T>): T
    {
        if (this.repos[cls.name] === undefined) {
            this.repos[cls.name] = new cls(this.http);
        }

        return this.repos[cls.name] as T;
    }

    get users(): UserRepo
    {
        return this.repo(UserRepo);
    }

    get wallet(): WalletRepo
    {
        return this.repo(WalletRepo);
    }

    get currency(): CurrencyRepo
    {
        return this.repo(CurrencyRepo);
    }

    get user(): User|null
    {
        return this._user;
    }

    set user(user: User|null)
    {
        this._user = user;
        this.eventBus.$emit('user', this.user);
    }
}
