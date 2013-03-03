package com.sportycal.sportycal;

import android.app.Activity;
import android.os.Bundle;
import android.webkit.WebView;
import android.webkit.WebViewClient;

public class Main extends Activity {
    /** Called when the activity is first created. */
    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.main);
        
        WebView wv = (WebView) findViewById(R.id.webView1);

        wv.getSettings().setJavaScriptEnabled(true);
        wv.loadUrl("http://m.sportycal.com/?an=1");
        
        wv.setWebViewClient(new MainClient());
        
    }
    
    private class MainClient extends WebViewClient {
        @Override
        public boolean shouldOverrideUrlLoading(WebView view, String url) {
            view.loadUrl(url);
            return true;
        }
    }
}