package com.example.webview;

import androidx.appcompat.app.AppCompatActivity;

import android.os.Bundle;
import android.webkit.WebChromeClient;
import android.webkit.WebSettings;
import android.webkit.WebView;

public class MainActivity extends AppCompatActivity {

    WebView mithun;
    WebSettings my;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        mithun = findViewById(R.id.web);
        my = mithun.getSettings();
        my.setJavaScriptEnabled(true);
        mithun.loadUrl("http://secure2pay.co.in/employeewellness/");
    }
}