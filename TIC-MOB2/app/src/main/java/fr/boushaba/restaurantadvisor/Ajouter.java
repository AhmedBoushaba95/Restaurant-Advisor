package fr.boushaba.restaurantadvisor;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

public class Ajouter extends AppCompatActivity {
    private TextView mTextViewResult;
    private RequestQueue mQueue;
    
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_ajouter);

        mTextViewResult = findViewById(R.id.text_view_result);

        mTextViewResult.setOnClickListener(new View.OnClickListener(){
            @Override
            public void onClick(View v) {

                startActivityDetail();
            }
        });




        mQueue = Volley.newRequestQueue(this);

                jsonParse();
    }

    private void jsonParse() {

        String url = "http://10.0.2.2:8000/api/get-all-resto";

        JsonObjectRequest request = new JsonObjectRequest(Request.Method.GET, url, null,
                new Response.Listener<JSONObject>() {
                    @Override
                    public void onResponse(JSONObject response) {
                        try {
                            JSONArray jsonArray = response.getJSONArray("restos");

                            for (int i = 0; i < jsonArray.length(); i++) {
                                JSONObject employee = jsonArray.getJSONObject(i);

                                int note = employee.getInt("note");
                                String restau = employee.getString("name");

                                mTextViewResult.append(restau + "    |    Note: " + String.valueOf(note) +"/10"+ "\n\n");
                            }
                        } catch (JSONException e) {
                            e.printStackTrace();
                        }
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                error.printStackTrace();
            }
        });

        mQueue.add(request);
    }
    private void startActivityDetail(){
        Intent intent = new Intent (this, detail.class);
        startActivity(intent);
    }
}
