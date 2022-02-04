<template>
    <div class="wallet">
        <div v-if="wallet">
            <p><strong>Balance</strong>: {{ wallet.balance }} {{ wallet.currency }}</p>
            <p><strong>Was refunded</strong>: {{ refunded.refunded }} {{ wallet.currency }} (from {{ refunded.from }})</p>

            <table v-if="history" class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Changing value</th>
                        <th>Reason</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in history" :key="item.date">
                        <td>{{ item.date }}</td>
                        <td>{{ item.type }}</td>
                        <td class="row">
                            <div class="col">{{ item.value }} {{ item.currency }}</div>
                            <div v-if="wallet.currency !== item.currency" class="col">
                                <small  class="text-muted ml-2">({{ item.wallet_value }} {{ wallet.currency }})</small>
                            </div>
                        </td>
                        <td>{{ item.reason }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script lang="ts">
import {Component, Vue} from "vue-property-decorator";
import {Wallet as WalletType, Refunded, History} from "@/components/backend-api/entities/wallet";

@Component
export default class Wallet extends Vue
{
    wallet: WalletType|null = null;
    refunded: Refunded|null = null;
    history: History[]|null = null;

    beforeMount(): void {
        if (!this.$api.user) {
            this.$router.push({name: 'login'});
            return;
        }

        const user = this.$api.user;

        this.$api.wallet.getWallet(user.wallet).then(resp => {
            this.wallet = typeof resp.data === 'string' ? JSON.parse(resp.data) : resp.data;
        })

        this.$api.wallet.getRefunds(user.wallet).then(resp => {
            this.refunded = typeof resp.data === 'string' ? JSON.parse(resp.data) : resp.data;
        })

        this.$api.wallet.getHistory(user.wallet).then(resp => {
            this.history = typeof resp.data === 'string' ? JSON.parse(resp.data) : resp.data;
        })
    }
}
</script>
