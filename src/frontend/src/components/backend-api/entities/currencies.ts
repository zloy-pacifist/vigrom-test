import {Repository} from "@/components/backend-api/entities/_abstract";
import {AxiosResponse} from "axios";

export type Currency = {
    id: number,
    code: string,
}

export class CurrencyRepo extends Repository
{
    list(): Promise<AxiosResponse<Currency[]|string>> {
        return this.http.get(`/currency/list`)
    }
}
