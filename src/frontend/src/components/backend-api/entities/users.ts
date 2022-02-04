import {Repository} from "@/components/backend-api/entities/_abstract";
import {AxiosResponse} from "axios";

export enum UserRole {
    USER = 0,
    ADMIN = 1,
}

export type User = {
    id: number|null,
    name: string,
    wallet: number,
    role: UserRole,
}

export type LoginError = {
    error: string,
}

export class UserRepo extends Repository
{
    info(): Promise<AxiosResponse<User|string>> {
        return this.http.get('auth/info')
    }

    login(login: string, password: string): Promise<AxiosResponse<User|LoginError|string>> {
        return this.http.post('auth/login', JSON.stringify({login, password}) , {
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            }
        })
    }

    list(): Promise<AxiosResponse<User[]|string>> {
        return this.http.get('user/list')
    }
}
