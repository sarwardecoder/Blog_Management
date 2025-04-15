<template>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="h3 text-center mb-4">
                            Reset Password
                        </h2>
                        <form @submit.prevent="submit">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input
                                    id="email"
                                    v-model="form.email"
                                    name="email"
                                    type="email"
                                    autocomplete="email"
                                    required
                                    class="form-control"
                                    placeholder="Enter your email address"
                                />
                            </div>

                            <div class="d-grid">
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >
                                    Send Password Reset Link
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { reactive } from "vue";
import { Inertia } from "@inertiajs/inertia";

export default {
    setup() {
        const form = reactive({
            email: "",
        });

        function submit() {
            Inertia.post("/forgot-password", form);
        }

        return { form, submit };
    },
};
</script>
