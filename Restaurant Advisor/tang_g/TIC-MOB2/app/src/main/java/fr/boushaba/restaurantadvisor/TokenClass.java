package fr.boushaba.restaurantadvisor;

import android.util.Log;

public class TokenClass {
    private String Token;

    public String getToken() {
        return this.Token;
    }

    public void onSuccess(String result) {
        this.Token = result;
        Log.w("Token", this.Token);
    }
}