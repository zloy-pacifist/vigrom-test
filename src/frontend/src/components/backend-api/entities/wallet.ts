import {Repository} from "@/components/backend-api/entities/_abstract";
import {AxiosResponse} from "axios";

export type Wallet = {
    balance: number,
    currency: string,
};

export type Refunded = {
    refunded: number,
    from: string,
};

export type History = {
    currency: string,
    date: string,
    reason: string,
    type: string,
    value: number,
    wallet_value: number,
};

export const HistoryTypeList = {
    '0': 'credit',
    '1': 'debit',
};

export const HistoryReasonList = {
    '0': 'deposit',
    '1': 'withdraw',
    '2': 'payment',
    '3': 'refund',
};

export type HistoryAdd = {
    wallet: number|'',
    currency: string|'',
    value: number,
    type: number|'',
    reason: number|'',
}

export class WalletRepo extends Repository
{
    getWallet(id: number): Promise<AxiosResponse<Wallet|string>> {
        return this.http.get(`/wallet/${id}/get`)
    }

    getRefunds(id: number): Promise<AxiosResponse<Refunded|string>> {
        return this.http.get(`/wallet/${id}/refunded`)
    }

    getHistory(id: number): Promise<AxiosResponse<History[]|string>> {
        return this.http.get(`/wallet/${id}/history`)
    }

    add(history: HistoryAdd): Promise<AxiosResponse<string>> {
        return this.http.post(`/wallet/add`, JSON.stringify(history) , {
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            }
        });
    }
}
