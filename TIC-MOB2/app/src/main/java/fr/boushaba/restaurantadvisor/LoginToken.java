package fr.boushaba.restaurantadvisor;

import android.app.Application;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonArrayRequest;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;

public class LoginToken extends AppCompatActivity {

    String token;
    TokenClass userToken = new TokenClass();

    private void login(final TokenClass callback) {
        RequestQueue request = Volley.newRequestQueue(this);
        String url = "http://10.0.2.2:8000/api/login";
        HashMap<String, String> params = new HashMap<String, String>();
        params.put("email", "tanggustin@gmail.com");
        params.put("password", "chamallow");
        JsonObjectRequest request_json = new JsonObjectRequest(url, new JSONObject(params),
                new Response.Listener<JSONObject>() {
                    @Override
                    public void onResponse(JSONObject response) {
                        try {
                            if (response.getJSONObject("success").getString("token") != null) {
                                final String Tmptoken = response.getJSONObject("success").getString("token");
                                callback.onSuccess(Tmptoken);
                            } else
                                Log.w("Failure", "Identifiant incorrect");
                        } catch (Exception e) {
                            Log.w("Failure", "Identifiant incorrect");
                        }
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Log.w("Failure", "Identifiant incorrect");
            }
        });
        request.add(request_json);
    }

    private void Register(final TokenClass callback) {
        RequestQueue request = Volley.newRequestQueue(this);
        String url = "http://10.0.2.2:8000/api/register";
        HashMap<String, String> params = new HashMap<String, String>();
        params.put("name", "test");
        params.put("email", "test2@gmail.com");
        params.put("age", "19");
        params.put("telephon", "0788255097");
        params.put("adress", "Quelque part");
        params.put("password", "test");
        params.put("c_password", "test");
        JsonObjectRequest request_json = new JsonObjectRequest(url, new JSONObject(params),
                new Response.Listener<JSONObject>() {
                    @Override
                    public void onResponse(JSONObject response) {
                        try {
                            if (response.getJSONObject("success").getString("token") != null) {
                                final String Tmptoken = response.getJSONObject("success").getString("token");
                                callback.onSuccess(Tmptoken);
                            } else
                                Log.w("Failure", "Identifiant incorrect");
                        } catch (Exception e) {
                            Log.w("Failure", "Identifiant incorrect");
                        }
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Log.w("Failure", error.toString());
            }
        });
        request.add(request_json);
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        //this.login(userToken);
        this.Register(userToken);
    }
}