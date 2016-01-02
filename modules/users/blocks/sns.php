<? # СоциальныеСети

if (array_key_exists('confnum', $arg)){
/*	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param = $_POST['param'])."' WHERE id = {$arg['confnum']}");

echo <<<EOF
	<form method="post">
		<input type="text" name="param" value="$param"> <input type="submit" value="Сохранить">
	</form>
EOF;*/

	return;
}
//$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));

?>
<div id="soc1" style="overflow:hidden; white-space:nowrap;">
	<a target="blank" title="Добавить в Twitter" href="http://twitter.com/home?status=<?=iconv('cp1251', 'utf8', $conf['settings']['title'])?>">
		<img alt="" src="data:image/png;base64,
iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJ
bWFnZVJlYWR5ccllPAAABAJJREFUeNq8V1toHFUY/ubM7GX20mWTjQlrL0sjCipEpDQVghFf9V18
kLSg4Kvx8lBBV+qLPvkoVGwDggo+VaVURagUpIrR0icT3KSWpptttibZ7G1mzjn+Z3eSzaYzs01z
+dmf2Rlmz/ed77+d1eDa+z//NZEyzfxAMpnDHtqdSmV+pV7Pf/D8U1MbDz+8fP3ct7MlebfBJRdy
T11hXCwsSYWpsLV3f5yeGD3y8PnxIxlIetDkci8FQETXoNH1p7kSpm/ePmkkIuH84wMp1BwBa4/B
lTUcIEwkRgbTmCmV80bKjOYYUarZHPtltFcoTIVtGDoj2QV09WQfjfIBCtswNAKmD5fe8v8xU8bk
p1dRqdtImiGcnRzDowcP7JwBYSpsZnPeYuPnb079iUYihnC2r3Wd/Hw68P3tuMI2HCF8d6+sbkYR
MsMb96VqM/D97eWCIAKuAn5mELiK1brJeCTw/W0RaCvAA3dkhvRuQkzfRQWIAO8RAn3T7pXZNQvj
b//Qc/EEJeyZiRGMHE0HVIJwFaAvviFg3QRYLIL+3EM9CViWg9Pf/I0Lbx3voQAPVoBtJUC31Dx6
t1wiulxrorRmoT8e8laAuwoIKeBHQW4JwXYs2xdH2lQ5I7zaQCcHFLivCg9IIB5ieOfZIThqdY+l
dWpCbg4QASl9S8tLgUuvPNaTAE1eFOsNNDj3Ca3bBxQLhe2rwJYZYdctzK5WdlyCumwrwBy3Arj0
dqXAZlf7+XXmP9/379c3OiFvJaGE8CnFONXzmtWR0UhE8cXVIo4Np3akgGBKAQ62OQm9fOxQop2I
rrOwgTKL4PSFOVxbqGK16fj+NshlpxGth8A7B05ko7j0b7XrWXowhbnFVbx38ZbvDmNhhlOjA3ju
kWTwMGorIOFI7xAMxQXGDydx+VaHBCMlBg/2Udhkq+N5Lu5wTF1bwdhw3Lu6ILsVED5lWK438fqT
adxucMwuW1vmxL3DqstCBqq2hKn7K8BaXapHvAory/joRAZPD8ag0e7v17N9McQMzXPNNqbodEIn
YMarlnn9zhLOHMtApcNXhQp+KdYDszxpMHzyTAaFuyWfELhJyKlT2Y7TiokIPERyXFlYRCYawRtP
HMDHxwcCCdg0aK4sFKlv3LsxdRZUmHz9SGbRTYh6Y83pfTRfrDVavqM/J6SOwmxXARfzN8rl3NGh
IVRsZ1+O5EnqJYViUY3jeSYEz/9+4yaq1TVkY1HKWGo2bnx201XSqbUVhsJSmAq7NWle/Ozrc6YZ
Pjl6+BCGM/1UPSFyY1d3rWJuOzb+WSrjNwKvNazz37360qmNUffC2S8nhIY811iOBlVgQj6IKVU1
qaYgyS6R//61l1t/z/8XYAAgnH68sQ+kegAAAABJRU5ErkJggg==">
	</a>
	<a target="blank" title="Поделиться в Facebook" href="http://www.facebook.com/sharer.php?u=http://<?=$_SERVER['HTTP_HOST']?>/">
		<img alt="" src="data:image/png;base64,
iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJ
bWFnZVJlYWR5ccllPAAAA4FJREFUeNq8l01oE0EUx/+zO/moTRsSW1u1CVEr/ULFCn7UiyjqySqe
RIW2IvQmnvwAi5GCXyevXrQi9ih+gQdBBPHrUEQFPahY2qqJ1bRpUpIm2RlnNumh2eiuW9MHCXmT
2fd78+a9NzsEBenY19flcLrC7oqKEMoo6VRqOJuZCT+/039D6kR+bd1/9noosLx7R8c6NNT7y8nH
WCSGxy/e4cvI6MCz2+d6yJZ9Z7qCDYGB3VvX41diBqPReFkdCNR5sbjKhYdPhzD27Ws3pQ5neHWw
Aa8//sCPiSTKLdFYEkt8HjSFgoiMj4ep0+EKTSYziMbTWCiJ/EqAe92QbAqiYDyeAmNYUJFMyaZE
fGU1DnA+L4NuN0Xn9ka0NdagQvwulhOXn8zRs5xAsiljHNo8l7+s1oPeA2vh91aiqnIRKFUNc7Ti
BQpdsikDn1f43S4VhzubsbzOD1911R/nlWJItogAA+P2Pehob8Cq4JK/wnVYCYZkUy7CwOax/5vW
LEWNz2s6rxSD61tQ2Au7sjJQaxi79eANBh+8teQU5dx+BHzVLpEDTsP4o+efLdmUbL0KwPPKv0ps
Mg0nNZZcZDxh+iwhJF8FXCSHIo6kjM1tcDod5iVX6jkBlWw9AtIBq3lw8fgmQxiL5fyxjYaxU1de
zdEVitkIcKjiVLaaB+1tzUWlxE3n5BPu5RxdMvM5wGUEiF4SVoQxzdZWFduXzHwViD9UYj0CzGbb
LLavFhZNZYeSisbK60Cx/fyiC0moKtYjsP3ozbk1f/WQYc7O3lumdnTmbCdUhKLZbEalerwVW0ph
0TQnQkqEkWwuZy+0mtEBK7YkU7JpNjWNTHoK6WTMXgRK5IQVW5m0Csmmy1pa0LS5A7movRdSphnL
sm3bLtPnmuo8iKgq6HR8GqlYArHohM0IrDCeEVHzCKQcHJJNiX47yVeCZuM8sHOSulRFZ5J8MspO
yOB1O2wmoWb4mEmli+pMna2w3PDEVBwB3yLbSVj8Mb0dCZZkSrbCZpLhT2NjqFByaA/4UOsRLxkO
9b87QMUWS9uSIVmSKdn65XRDZ991T83S7lZxXar3+0VYFCiqNSeO7A0axq7dHSlZLdK5SCyGD8Mj
SPz8PjB0r7+HzE7YsOd0l+KqDGuEhmReWc3HkwdbDWOXBt8bOx+Rb0GiBfPcMJuZDg/dv6Bfz38L
MADtLONxPx43OAAAAABJRU5ErkJggg==">
	</a>
	<a target="blank" title="Поделиться ВКонтакте" href="http://vkontakte.ru/share.php?url=http://<?=$_SERVER['HTTP_HOST']?>/">
		<img alt="" src="data:image/png;base64,
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
QmCC">
	</a>
	<a target="blank" title="Добавить в Google Buzz" href="http://www.google.com/reader/link?url=http://<?=$_SERVER['HTTP_HOST']?>/&amp;title=<?=iconv('cp1251', 'utf8', $conf['settings']['title'])?>&amp;srcURL=http://<?=$_SERVER['HTTP_HOST']?>/">
<!--	<img alt="" src="/img/sns/google-buzz.png"></a><a target="blank" title="Добавить в FriendFeed" href="http://www.friendfeed.com/share?title=%D0%9A%D0%BD%D0%BE%D0%BF%D0%BA%D0%B8%20%D1%81%D0%BE%D1%86%D0%B8%D0%B0%D0%BB%D1%8C%D0%BD%D1%8B%D1%85%20%D1%81%D0%B5%D1%82%D0%B5%D0%B9%26nbsp%3B%26mdash%3B%20%D0%BD%D0%B0%20%D0%B1%D0%BB%D0%BE%D0%B3! - http%3A%2F%2Fniklobanov.com%2F%3Fp%3D1219">-->
		<img alt="" src="data:image/jpeg;base64,
iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJ
bWFnZVJlYWR5ccllPAAAA7FJREFUeNq8V1tIFFEY/ueyXjZNJbp4SRcML1QKZZAR2FtkVhQVPQRa
T74VFYU9LUQFQRG99aSSDxUZ9GCgEJQEgRqhKaGmrpUX2lJbdd11Zs7p/DM7sxd37Wyy/su4O+d8
/t93/tvOChCwE9dbatPtdmfWxjQHJNBmPQuuea/X+ere+Wa8F/DP6YanjcUFOXUHyhywOdOeSH5w
z3mha+AbDIz+aHpx99wF4fi1J7W7dxQ0Ve0pBEGUYdGnJlTAhhQZKFHhTfcwfBn7XifbU1OcJY5s
WPBTWPL7INGGB0xNlmBXYS6MT7udMsu7Q5Tw5BqslyEXciK3LLMPfo2CJFJYT1MIBeRmAiQQBBHI
+vLrnMgta4Swolhn9oAht0wYufYPAX5Fhe6Rn9A3/hv+eJdj4q7UlHPhGk7u1d+RW9YoXTX8bo8X
nr0fYiKMIpWF2NjHHf1cOJMPuVkECFuIruCXZwlefhgGTSNQWbIFbpwpg9LtmStwO+tbDYecOJOP
mCmIJmCZnaS9ZwyISuBUZQHcqq2IeSKbYBz32P58LlxQADUFrAQPTsyC169CaX7Wqk7RJFGEorwM
LlxoCgI1QKIW4djknJ7H+uqSf1ZzPDij+mmgBohRAxRfERo8Cz6QWMiqyrPD1jv7puB2yyeYX1KC
J4sDZ6YAP1o1gEZDFCgs7zYxehm3vhsFH0tNrH0eHNUFCME2RGvrHIwIV3SC/pGZmHs8uCMHixkx
AJZDoA2N8EuiwDW91oozO4DSQAQIKwQUaqp9ff/oqo47HtRwCYjEVV9tC+sAvQaMIoS4IvC/ZvrX
rJoDPRV6G6IaSUiwgIghhLd6G5rfhJIICY4AWCe3usGqgZApdfZme9g/Pr9zOOw+cp8XF5yCgQhA
oAbMNuSNwFpxoUPXakMcCoXFeWFA19BkjFwanh1FOXHhItvQGkTBbya+PjbXTTwvLtZMkDVNA1VR
wtRxObZSx4eL3ENO5GYCVHbDZrYswdJyPALiw4VasiTonMgtqsqya8o9A+nsFwuh1LrQQTTn5nq8
uNALuZATuUWiKc7PI+PgW1yA7IxkSE2S9ArGwRFtOJnrlgBOHBr6Rg7kQk7klrsaLzXvu/jo0NuP
/XVlhQWQu3UT2OxJIMhZUUNbkm+sSxlpRislUy4c5lxh18TEFPR+dcH8oqepm3Fb0ivqHtaCKDsF
yeYQ2NCwqcZWefG2MMe9g9PGM4PNaDObQrhwQDU2+djDj6a42IOms6fpsv7z/K8AAwDzRzhim9sz
mQAAAABJRU5ErkJggg==">
	</a>
	<a target="blank" title="Поделиться в Моем Мире" href="http://connect.mail.ru/share?share_url=http://<?=$_SERVER['HTTP_HOST']?>/">
		<img alt="" src="data:image/jpeg;base64,
iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJ
bWFnZVJlYWR5ccllPAAAAYBQTFRFVWeHYILCSlhw2p0Zgnxq5qMSZniXmoFKNlSMtI45xpUpWWV4
5aIROViUWn3ASXC6ssLifIeVaWlmZHuldH2KooM9TGqjVnq/O1ya6KUTfXNavJEyM06EtpNE5KIR
tplVmYplbYi5l4NTPF2cZofFQ2iuRm24boSp0ZoiUm+k3aAbSF6IjYRtS2STqYpCTG2tPmGjvpQ2
0pwlvZI0k3xGxZs+oJJuvZU8RmGVMEt+QWarOFaQX3elQGSnTHCzY4TDXYDBUne9RGqyVXm+RWy1
T3W8UHW8XH/BsbrMs77SXGBhqpltkKjVhJ7QOlqXU2mTpLfcgpW5fIypm5WDipGZeXlybYzHRWu0
yZ5Bzp40V3u8NFGIl4Vbe4+wP1aC1J0lrYg2rJVgiIZ8anSDvZlKiIyKoItYZ254bnR+qJFcwJQz
wJU4Q2myTmmbU3S0VHi7uY4wbIvHaorGQWWqP2KlLUZ1zdjsiqPSPmCh56QTdJLKPV6eQ2ivcpDJ
b47I////vYGouAAAAIB0Uk5T////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////wA4BUtnAAACKUlEQVR42nzTWVfaUBQF4AN4
FDFBtAKpxsoQkEGU1hGnACJhcJ4VLR1ta1trxZbeJjZ/vfcmCC/Gb62s7OzsJE8BXS/HNQvxsq6D
vuv3/LbgUXZ1KPs9O00LO55cGeIbzb+WmhtxaGzeP2GzAdr9vyfca1A4+tlxsV+tVvcvOsVRAfK3
bQcnz00nB+0uD5rUEhxj9+pXCXYaC7ZaDSKyKXhKSKwLUt9TfaEEIaetOgINm2mMkMnU8tyH2bnx
OFwR8tasG7CSNQwQ0uv7SB+l5mZDvYSMG/0K3LkNoySxTUexnpCLTr5uJ8gXo7+DtDmgHzirE1fK
51sYJuTqbJLUjT4Nqp8ZQDykB/ygeWoEp/2HiMYNFVT3GjWE2B3A2DXLayGcZsUzGt0qhN05il2f
oytnOMdpVnTT6A6DoBSpbsShz3TEcvEljhQnEFdpVATYyq5Tqw68nIrhKMvvEEfWRzHGcnaLDvoZ
LzquBxFfBQLzSAfLiD2spgPeZgw+Ic5HBh3I3NAhOoDVNh6WbFFmcYbj5n2vh71vZuy8g+O44W+s
ti2BUy4YBC/HJS5hi1/omujz3gymjVZ20kHexNN3tLznnWmzpIOaFGnh7V7HC8aVeqgiUg1EqfEg
7czYK5WKXVxpV5IIoty46wgLgqB2LhdlETLy8R9Lx3IGklFFtaREk1CqZRUtLTxC1ZRsrQT6ntgv
3T7209xK/eIe/bv1UvKXhWRJ1/8LMADjYx57SGHVnAAAAABJRU5ErkJggg==">
	</a>
	<a target="blank" title="Опубликовать в своем блоге livejournal.com" href="http://www.livejournal.com/update.bml?event=http://<?=$_SERVER['HTTP_HOST']?>/&amp;subject=<?=iconv('cp1251', 'utf8', $conf['settings']['title'])?>">
		<img alt="" src="data:image/jpeg;base64,
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">
	</a>
</div>
