<template>
    <div class="wallet">
        <div v-if="users && currencies">
            <div class="row mt-5 mb-10 row-cols-2 justify-content-center">
                <div class="col">
                    <div class="card">
                        <div class="card-body form">
                            <div class="form-group">
                                <label for="wallet" class="form-label">User</label>
                                <select name="wallet" id="wallet" class="form-control" v-model="form.wallet"
                                    :class="errors && errors.wallet ? 'is-invalid' : ''"
                                >
                                    <option value="">-- Select wallet --</option>
                                    <option v-for="user in users" :key="user.wallet" :value="user.wallet">{{ user.name }}</option>
                                </select>
                                <div v-if="errors && errors.wallet" class="invalid-feedback">
                                    {{ errors.wallet }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="currency" class="form-label">Currency</label>
                                <select name="currency" id="currency" class="form-control" v-model="form.currency"
                                    :class="errors && errors.currency ? 'is-invalid' : ''"
                                >
                                    <option value="">-- Select currency --</option>
                                    <option v-for="currency in currencies" :key="currency.id" :value="currency.code">{{ currency.code }}</option>
                                </select>
                                <div v-if="errors && errors.currency" class="invalid-feedback">
                                    {{ errors.currency }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="types" class="form-label">Type</label>
                                <select name="types" id="types" class="form-control" v-model="form.type"
                                    :class="errors && errors.type ? 'is-invalid' : ''"
                                >
                                    <option value="">-- Select type --</option>
                                    <option v-for="(type, index) in types" :key="index" :value="index">{{ type }}</option>
                                </select>
                                <div v-if="errors && errors.type" class="invalid-feedback">
                                    {{ errors.type }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="value" class="form-label">Value</label>
                                <input type="number" id="value" step="0.0001" name="value" class="form-control" v-model="form.value"
                                       :class="errors && errors.value ? 'is-invalid' : ''"
                                />
                                <div v-if="errors && errors.value" class="invalid-feedback">
                                    {{ errors.value }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="reason" class="form-label">Reason</label>
                                <select name="reason" id="reason" class="form-control" v-model="form.reason"
                                    :class="errors && errors.reason ? 'is-invalid' : ''"
                                >
                                    <option value="">-- Select reason --</option>
                                    <option v-for="(reason, index) in reasons" :key="index" :value="index">{{ reason }}</option>
                                </select>
                                <div v-if="errors && errors.reason" class="invalid-feedback">
                                    {{ errors.reason }}
                                </div>
                            </div>
                            <div v-if="errors && errors.success" class="text-success">
                                <span>New history record has been added!</span>
                            </div>
                            <div class="form-group text-center">
                                <button type="button" class="btn btn-primary" v-on:click="submit">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import {Component, Vue} from "vue-property-decorator";
import {
    HistoryReasonList,
    HistoryTypeList, HistoryAdd
} from "@/components/backend-api/entities/wallet";
import {User} from "@/components/backend-api/entities/users";
import {Currency} from "@/components/backend-api/entities/currencies";

@Component
export default class WalletAdd extends Vue
{
    users: User[]|null = null;
    currencies: Currency[]|null = null;

    types = HistoryTypeList;

    reasons = HistoryReasonList;

    form: HistoryAdd = {
        wallet: '',
        currency: '',
        value: 0,
        type: '',
        reason: '',
    }

    errors: {[key: string]: string} = {};

    beforeMount(): void {
        if (!this.$api.user) {
            this.$router.push({name: 'login'});
            return;
        }

        const user = this.$api.user;

        this.$api.users.list().then(resp => {
            this.users = typeof resp.data === 'string' ? JSON.parse(resp.data) : resp.data;
        })

        this.$api.currency.list().then(resp => {
            this.currencies = typeof resp.data === 'string' ? JSON.parse(resp.data) : resp.data;
        })
    }

    async submit(): Promise<void>
    {
        this.errors = {};

        const resp = await this.$api.wallet.add(this.form);

        if (resp.status === 200) {
            this.$set(this.errors, 'success', '(ﾉಥ益ಥ）ﾉ彡┻━┻');
        } else {
            const data = typeof resp.data === 'string' ? JSON.parse(resp.data) : null;

            if (data.errors) {

                for (const key in data.errors) {
                    const arr: string = data.errors[key];
                    this.$set(this.errors, key, arr[0])
                }
            }
        }
    }
}
</script>
