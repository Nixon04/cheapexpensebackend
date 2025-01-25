<template>
    <div v-if="stablevisible" class="transfercontainer">
        <div class="bg-transfer-padding-all">
            <div class="d-flex justify-content-end">
                <div class="bg-circle" @click="dismissmodalclear">
                    <div class="fas fa-x fas-sm"></div>
                </div>
            </div>
            <div class="headers">
                <h1 class="bg-mealcolor fs-4 text-center mb-4 fw-bold">MealXpress</h1>
                Withdraw your Earnings
            </div>

            <div class="content-transfer-body">
                <div class="form-input mb-3 py-3">
                    <select name="" class="form-select py-3 mb-3" v-model="accountcode">
                        <option v-for="(items, index) in populatebanks" :key="index" :value="items.bankid">
                            {{ items.bank_name }}
                        </option>
                    </select>
                    <div class="form-input mb-5">
                        <input
                            type="text"
                            class="py-3 form-control"
                            v-model="accountnumber"
                            maxlength="10"
                            placeholder="Account Number"
                        />
                    </div>

                    <!-- Display fetched account name -->
                    <div v-if="fetching">Loading....</div>
                    <div v-if="username" class="alert alert-success mb-3">
                        Account Name: {{ username }}
                    </div>

                    <input type="text" :value="recipientname" class="form-control d-none">

                    <div class="form-input mb-5">
                        <input
                            type="text"
                            class="py-3 form-control"
                            :value="formattedAmount"
                            maxlength="10"
                            placeholder="Amount"
                            @input="UpdatedAmount"
                        />
                    </div>

                    <div class="form-input mb-5">
                        <input
                            type="text"
                            class="py-3 form-control"
                            v-model="reasons"
                            placeholder="Reasons(Optional)"
                        />
                    </div>

                    <!-- <button @click="showToast" class="btn bg-button-submit mb-3">Toast me</button> -->

                    <div class="form-input">
                        <button class="bg-button-submit" :disabled="!username" @click="requerydata">Proceed</button>
                        <div v-if="isloading"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import _ from "underscore";
import { useToast } from "vue-toastification";

const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

const delay = (ms) => new Promise(resolve => setTimeout(resolve, ms));

export default {
    props: {
        stablevisible: {
            type: Boolean,
            required: true,
        },
        populatebanks: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            accountnumber: "",
            amount: "",
            accountcode: "",
            reasons: "",
            username: "",
            delay,
            isloading: false,
            recipientname: "",
            fetching: false, // Flag to prevent multiple API calls
        };
    },
    computed: {
        formattedAmount() {
            if (!this.amount) return "";
            return this.amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
    },
    watch: {
        accountnumber: _.debounce(function(newVal) {
            if (newVal.length === 10 && !this.fetching) {
                this.isloading = true;
                this.fetchAccountName(newVal);
            }
        }, 3000),
    },
    methods: {

        // Method to show toast notification
        showToast() {
            const toast = useToast();
            toast.success("This is a toast message!", {
                timeout: 3000,
                hideProgressBar: true,
                icon: false,
            });
        },

        // Method to proceed with the transfer
        async requerydata() {
            const toast = useToast();
            if (!this.username || !this.accountnumber || !this.amount) {
                console.log('Please all fields are required');
                return;
            }
            const transferData = {
                account_number: this.accountnumber,
                account_code: this.accountcode,
                amount: this.amount,
                reasons: this.reasons,
                recipient_id: this.recipientname,
            };

            try {
                this.isloading = true;
                await delay(3000);
                const response = await axios.post("/vendorspath/localtransfer", transferData, {
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        "Content-Type": "application/json",
                    },
                });

               if(response.status){
                toast.success("successful", {
                    timeout: 3000,
                    hideProgressBar: true,
                    icon: false,
                });
               }else{
                toast.error("Error occurred", {
                    timeout: 3000,
                    hideProgressBar: true,
                    icon: false,
                });
               }
            } catch (error) {
                toast.error("An error occurred. Please try again.", {
                    timeout: 3000,
                    hideProgressBar: true,
                    icon: false,
                });
            } finally {
                this.isloading = false;
            }
        },

        // Method to clear the form
        clearForm() {
            this.accountnumber = "";
            this.amount = "";
            this.reasons = "";
            this.username = "";
            this.recipientname = "";
            this.accountcode = "";
        },

        // Method to update the amount
        UpdatedAmount(event) {
            const inputValue = event.target.value.replace(/,/g, "").replace(/[^0-9]/g, "");
            if (!isNaN(inputValue) && inputValue !== "") {
                this.amount = parseInt(inputValue, 10);
            } else if (inputValue === "") {
                this.amount = "";
            }
        },

        // Method to fetch the account name based on account number
        async fetchAccountName(accountNumber) {
            try {
                this.fetching = true;
                const response = await axios.post(
                    "/vendorspath/createrecipient",
                    { account_number: accountNumber, account_code: this.accountcode },
                    {
                        headers: {
                            "X-CSRF-TOKEN": csrfToken,
                            "Content-Type": "application/json",
                        },
                    }
                );
                this.username = response.data.message.account_name || "Name not found";
                this.recipientname = response.data.message.recipient || 'No Ref Id found';
            } catch (error) {
                console.error("Error fetching account name:", error);
                this.username = "Error fetching name";
            } finally {
                this.fetching = false; // Reset fetching flag
            }
        },

        // Method to dismiss the modal and clear the form
        dismissmodalclear() {
            this.$emit("close");
            this.clearForm();
        }
    }
}
</script>

<style scoped>
/* Add relevant CSS styles here */
</style>
