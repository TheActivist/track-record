# track-record
A Wordpress plug-in using AlphaVantage data to track performance of short sales.

This is a simple plug-in that uses the daily adjusted time series data from AlphaVantage (alphavantage.co) to track the performance of short sales of stock we publish on The Activist (theactivist.news). It uses a shortcode (trackrec) with two parameters: symbol (a stock's ticker symbol) and date (the date on which we published a report on the stock). After fetching the historical prices of the stock from AlphaVantage, it locates the most recent closing price of the stock as of the date/time we published. Then it locates the lowest intraday price of the stock since the date/time we published. Then it calculates the percentage change between the two prices. Then it presents the data in an HTML table row. That's it.

We fully acknowledge that using the lowest intraday price since publication portrays the performance of our short calls in the best possible light. In reality, no trader has such timing or luck. But since we don't trade the stocks we analyze, the plug-in doesn't track real trading. Rather, it shows what was possible under ideal circumstances and what was available for traders to grab a piece of. As we continue to develop this plug-in, we may introduce more sophisticated methods of tracking performance. For now, this works.
