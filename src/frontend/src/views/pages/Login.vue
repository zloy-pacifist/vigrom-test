<template>
    <div class="login">
        <div class="row mt-5 mb-10 row-cols-2 justify-content-center">
            <div class="col">
                <div class="card">
                    <div class="card-body form">
                        <div class="form-group">
                            <label for="login" class="form-label">Login</label>
                            <input type="text" id="login" name="login" class="form-control" v-model="login" />
                        </div>
                        <div class="form-group">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" name="password" class="form-control" v-model="password"
                                :class="errors && errors.password ? 'is-invalid' : ''"
                            />
                            <div v-if="errors && errors.password" class="invalid-feedback">
                                {{ errors.password }}
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <button type="button" class="btn btn-primary" v-on:click="submit">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import {Component, Vue} from 'vue-property-decorator';

@Component
export default class Login extends Vue
{
    login: string|null = null;
    password: string|null = null;

    errors = {};

    async submit(): Promise<void> {
        this.errors = {};
        const resp = await this.$api.users.login(this.login || '', this.password || '');
        const data = typeof resp.data === 'string' ? JSON.parse(resp.data) : resp.data;

        if (resp.status !== 200) {
            if (data.error) {
                this.errors = {
                    password: data.error,
                };
            }
        } else {
            this.$api.user = data;
        }
    }

    mounted(): void {
        if (this.$api.user) {
            this.$router.push({
                name: 'wallet'
            })
        }
    }
}
</script>
