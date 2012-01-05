<?php

require_once 'UnitTestCommon.php';

class Services_Facebook_GroupsTest extends Services_Facebook_UnitTestCommon
{

    public function testGetMembers()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<groups_getMembers_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd">
  <members list="true">
    <uid>714455356</uid>
    <uid>1203424698</uid>
    <uid>1060999433</uid>
    <uid>830614423</uid>
    <uid>745936519</uid>
    <uid>586036296</uid>
    <uid>528180774</uid>
    <uid>620431575</uid>
    <uid>585631432</uid>
    <uid>1047480086</uid>
    <uid>521083801</uid>
    <uid>728539606</uid>
    <uid>572296682</uid>
    <uid>560681217</uid>
    <uid>1047480140</uid>
    <uid>536535894</uid>
    <uid>594708530</uid>
    <uid>721775649</uid>
    <uid>803010503</uid>
    <uid>710265293</uid>
    <uid>713206852</uid>
    <uid>707802443</uid>
    <uid>572062896</uid>
    <uid>547217860</uid>
    <uid>646061993</uid>
    <uid>579358722</uid>
    <uid>672653037</uid>
    <uid>653330755</uid>
    <uid>520392361</uid>
    <uid>30610340</uid>
    <uid>554268300</uid>
    <uid>615827938</uid>
    <uid>518481798</uid>
    <uid>711915061</uid>
    <uid>614667853</uid>
    <uid>547304461</uid>
    <uid>670956942</uid>
    <uid>763849697</uid>
    <uid>697048711</uid>
    <uid>662764289</uid>
    <uid>545215344</uid>
    <uid>571691452</uid>
    <uid>604358504</uid>
    <uid>571503332</uid>
    <uid>675227245</uid>
    <uid>574255686</uid>
    <uid>1047480162</uid>
    <uid>720136749</uid>
    <uid>639057390</uid>
    <uid>683226814</uid>
    <uid>627311631</uid>
    <uid>567353225</uid>
    <uid>713872353</uid>
    <uid>718307060</uid>
    <uid>658641336</uid>
    <uid>564951015</uid>
    <uid>656446026</uid>
    <uid>569155122</uid>
    <uid>618406494</uid>
    <uid>563101706</uid>
    <uid>581546015</uid>
    <uid>732231862</uid>
    <uid>544361673</uid>
    <uid>709260942</uid>
    <uid>833355273</uid>
    <uid>706776770</uid>
    <uid>659070267</uid>
    <uid>1047480089</uid>
    <uid>602525209</uid>
    <uid>1047480099</uid>
    <uid>1047480052</uid>
    <uid>688950116</uid>
    <uid>1047480027</uid>
    <uid>10732470</uid>
    <uid>1047480049</uid>
    <uid>1047480126</uid>
    <uid>639965261</uid>
    <uid>691780182</uid>
    <uid>1047480014</uid>
    <uid>1047480138</uid>
    <uid>1047480087</uid>
    <uid>543695617</uid>
    <uid>1047480034</uid>
    <uid>1047480100</uid>
    <uid>615635031</uid>
    <uid>1047480094</uid>
    <uid>1047480040</uid>
    <uid>505150091</uid>
    <uid>690210392</uid>
    <uid>570430704</uid>
    <uid>1047480047</uid>
    <uid>502773343</uid>
    <uid>508783820</uid>
    <uid>1047480134</uid>
    <uid>1047480179</uid>
    <uid>688735577</uid>
    <uid>1047480082</uid>
    <uid>1047480028</uid>
    <uid>1244041233</uid>
    <uid>1047480188</uid>
  </members>
  <not_replied list="true"/>
</groups_getMembers_response>
XML;

        $this->mockSendRequest($response);
        $result = $this->instance->getMembers(683226814);
        $this->assertEquals(count($result), 100);
        foreach ($result as $uid) {
            $this->assertTrue(is_numeric($uid));
        }
    }

    public function testGet()
    {
        $response = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<groups_get_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.facebook.com/1.0/ http://api.facebook.com/1.0/facebook.xsd" list="true">
  <group>
    <gid>31552233595</gid>
    <name>Obama Out of Afghanistan</name>
    <nid>0</nid>
    <description> KABUL, Afghanistan (CNN) - US President-Elect Barack Obama said that United States needs to focus on Afghanistan in its battle against terrorism.

&quot;I think one of the biggest mistakes we've made strategically after 9/11 was to fail to finish the job here, focus our attention here. We got distracted by Iraq,&quot; he said.

President-elect Barack Obama also intends to renew the U.S. commitment to the hunt for Osama bin Laden, a priority the president-elect believes President Bush has played down after years of failing to apprehend the al-Qaeda leader. Critical of Bush during the campaign for what he said was the president's extreme focus on Iraq at the expense of Afghanistan, Obama also intends to move ahead with a planned deployment of thousands of additional U.S. troops there.

The emerging broad strokes of Obama's approach are likely to be welcomed by a number of senior U.S. military officials who advocate a more aggressive and creative course for the deteriorating conflict. Taliban attacks and U.S. casualties this year are the highest since the war began in 2001. 

Obama said troop levels must increase in Afghanistan. &quot;For at least a year now, I have called for two additional brigades, perhaps three,&quot; he told CBS. &quot;I think it's very important that we unify command more effectively to coordinate our military activities. 

&quot;The Afghan government needs to do more. But we have to understand that the situation is precarious and urgent here in Afghanistan. And I believe this has to be our central focus, the central front, on our battle against terrorism,&quot; Obama said on CBS' &quot;Face the Nation.&quot;

Now that Obama has been elected as the US President, the opposition to his plan in Afghanistan has to grow. The US is already increasing it troops in large numbers in January and Obama has not said anything to change his plan for Afghanistan. So invite other to join us to help build this movement to end the occupation of Afghanistan. 
</description>
    <group_type>Common Interest</group_type>
    <group_subtype>Politics</group_subtype>
    <recent_news>Don't Let Barack Obama Break Your Heart
Why Americans Shouldn't Go Home

By Tom Engelhardt

On the day that Americans turned out in near record numbers to vote, a record was set halfway around the world. In Afghanistan, a U.S. Air Force strike wiped out about 40 people in a wedding party. This represented at least the sixth wedding party eradicated by American air power in Afghanistan and Iraq since December 2001.

American planes have, in fact, taken out two brides in the last seven months. And don't try to bury your dead or mark their deaths ceremonially either, because funerals have been hit as well. Mind you, those planes, which have conducted 31% more air strikes in Afghanistan in support of U.S. troops this year, and the missile-armed unmanned aerial vehicles (UAVs) now making almost daily strikes across the border in Pakistan, remain part of George W. Bush's Air Force, but only until January 21, 2009. Then, they -- and all the brides and grooms of Afghanistan and in the Pakistani borderlands who care to have something more than the smallest of private weddings -- officially become the property of President Barack Obama.

That's a sobering thought. He is, in fact, inheriting from the Bush administration a widening war in the region, as well as an exceedingly tenuous situation in devastated, still thoroughly factionalized, sectarian, and increasingly Iranian-influenced Iraq. There, the U.S. is, in actuality, increasingly friendless and ever less powerful. The last allies from the infamous &quot;coalition of the willing&quot; are now rushing for the door. The South Koreans, Hungarians, and Bulgarians -- I'll bet you didn't even know the latter two had a few troops left in Iraq -- are going home this year; the rump British force in the south will probably be out by next summer.

The Iraqis are beginning to truly go their own way (or, more accurately, ways); and yet, in January, when Barack Obama enters office, there will still be more American troops in Iraq than there were in April 2003 when Baghdad fell. Winning an election with an antiwar label, Obama has promised -- kinda -- to end the American war there and bring the troops -- sorta, mostly -- home. But even after his planned 16-month withdrawal of U.S. &quot;combat brigades,&quot; which may not be welcomed by his commanders in the field, including former Iraq commander, now Centcom Commander David Petraeus, there are still plenty of combative non-combat forces, which will be labeled &quot;residual&quot; and left behind to fight &quot;al-Qaeda.&quot; Then, there are all those &quot;advisors&quot; still there to train Iraqi forces, the guards for the giant bases the Bush administration built in the country, the many thousands of armed private security contractors from companies like Blackwater, and of course, the 1,000 &quot;diplomats&quot; who are to staff the newly opened U.S. embassy in Baghdad's Green Zone, possibly the largest embassy on the planet. Hmmmm.

And while the new president turns to domestic matters, it's quite possible that significant parts of his foreign policy could be left to the oversight of Vice President Joe Biden who, in case anyone has forgotten, proposed a plan for Iraq back in 2007 so filled with imperial hubris that it still startles. In a Caesarian moment, he recommended that the U.S. -- not Iraqis -- functionally divide the country into three parts. Although he preferred to call it a &quot;federal system,&quot; it was, for all intents and purposes, a de facto partition plan.

If Iraq remains a sorry tale of American destruction and dysfunction without, as yet, a discernable end in sight, Afghanistan may prove Iraq squared. And there, candidate Obama expressed no desire to wind the war down and withdraw American troops. Quite the opposite, during the election campaign he plunked hard for escalation, something our NATO allies are sure not to be too enthusiastic about. According to the Obama plan, many more American troops (if available, itself an open question) are to be poured into the country in what would essentially be a massive &quot;surge strategy&quot; by yet another occupant of the Oval Office. Assumedly, the new Afghan policy would be aided and abetted by those CIA-run UAVs directed toward Pakistan to hunt down Osama bin Laden and pals, while undoubtedly further destabilizing a shaky ally.

When it comes to rising civilian casualties from U.S. air strikes in their countries, both Afghan President Hamid Karzai and Pakistani President Asif Ali Zardari have already used their congratulatory phone calls to President-elect Obama to plead for an end to the attacks, which produce both a profusion of dead bodies and a profusion of live, vengeful enemies. Both have done the same with the Bush administration, Karzai to the point of tears.

The U.S. military argues that the use of air power is necessary in the face of a spreading, ever more dangerous, Taliban insurgency largely because there are too few boots on the ground. (&quot;If we got more boots on the ground, we would not have to rely as much on airstrikes&quot; was the way Army Brig. Gen. Michael Tucker, deputy commander of NATO forces in Afghanistan, put it.) But rest assured, as the boots multiply on increasingly hostile ground, the military will discover it needs more, not less, air power to back more troops in more trouble.

So, after January 20th, expect Obama to take possession of George Bush's disastrous Afghan War; and unless he is far more skilled than Alexander the Great, British empire builders, and the Russians, his war, too, will continue to rage without ever becoming a raging success.

Finally, President-elect Obama accepted the overall framework of a &quot;Global War on Terror&quot; during his presidential campaign. This &quot;war&quot; lies at the heart of the Bush administration's fantasy world of war that has set all-too-real expanses of the planet aflame. Its dangers were further highlighted this week by the New York Times, which revealed that secret orders in the spring of 2004 gave the U.S. military &quot;new authority to attack the Qaeda terrorist network anywhere in the world, and a more sweeping mandate to conduct operations in countries not at war with the United States.&quot;

At least twelve such attacks have been carried out since then by Special Operations forces on Pakistan, Somalia, most recently Syria, and other unnamed countries. Signed by Donald Rumsfeld, signed off on by President Bush, built-upon recently by Secretary of Defense Robert Gates, these secret orders enshrine the Pentagon's right to ignore international boundaries, or the sovereignty of nations, in an endless global &quot;war&quot; of choice against small, scattered bands of terrorists.

As reporter Jim Lobe pointed out recently, a &quot;series of interlocking grand bargains&quot; in what the neoconservatives used to call &quot;the Greater Middle East&quot; or the &quot;arc of instability&quot; might be available to an Obama administration capable of genuinely new thinking. These, he wrote, would be &quot;backed by the relevant regional players as well as major global powers -- aimed at pacifying Afghanistan; integrating Iran into a new regional security structure; promoting reconciliation in Iraq; and launching a credible process to negotiate a comprehensive peace between Israel and the Arab world.&quot;

If, however, Obama accepts a War on Terror framework, as he already seems to have, as well as those &quot;residual&quot; forces in Iraq, while pumping up the war in Afghanistan, he may quickly find himself playing by Rumsfeld rules, whether or not he revokes those specific orders. In fact, left alone in Washington, backed by the normal national security types, he may soon find himself locked into all sorts of unpalatable situations, as once happened to another Democratic president, Lyndon Baines Johnson, who opted to escalate an inherited war when what he most wanted to do was focus on domestic policy.

Previews for a Political Zombie Movie

Domestically, it's clear enough that we are about to leave the age of Bush -- in tone and policy -- but what that leave-taking will consist of is still an open question. This is especially so given a cratering economy and the pot-holed road ahead. It is a moment when Obama has, not surprisingly, begun to emphasize continuity and reassurance alongside his campaign theme of &quot;change we can believe in.&quot;

All you had to do was look at that array of Clinton-era economic types and CEOs behind Obama at his first news conference to think: been there, done that. The full photo of his economic team that day offered a striking profile of pre-Bush era Washington and the Washington Consensus, and so a hint of the Democratic world the new president will walk into on January 20, 2009.

How about former Treasury Secretaries Robert Rubin and Larry Summers, those kings of 1990s globalization, or even the towering former Fed chief from the first Bush era, Paul Volcker? Didn't that have the look of previews for a political zombie movie, a line-up of the undead? As head of the New America Foundation Steve Clemons has been writing recently, the economic team looks suspiciously as if it were preparing for a &quot;Clinton 3.0&quot; moment.

You could scan that gathering and not see a genuine rogue thinker in sight; no off-the-reservation figures who might represent a breath of fresh air and fresh thinking (other than, being hopeful, the president-elect himself). Clemons offers an interesting list of just some obvious names left off stage: &quot;Paul Krugman, Joseph Stiglitz, Jeffrey Sachs, James Galbraith, Leo Hindery, Clyde Prestowitz, Charlene Barshefsky, C. Fred Bergsten, Adam Posen, Robert Kuttner, Robert Samuelson, Alan Murray, William Bonvillian, Doug &amp; Heidi Rediker, Bernard Schwartz, Tom Gallagher, Sheila Bair, Sherle Schwenninger, and Kevin Phillips.&quot;

Mobilizing a largely Clintonista brain trust may look reassuring to some -- an in-gathering of all the Washington wisdom available before Hurricane Bush/Cheney hit town, but unfortunately, we don't happen to be entering a Clinton 3.0 moment. What's globalizing now is American disaster, which threatens to level a vulnerable world.

In a sense, though, domestic policy may, relatively speaking, represent the good news of the coming Obama era. We know, for instance, that those preparing the way for the new president's arrival are thinking hard about how to roll back the worst of Bush cronyism, enrich-yourself-at-the-public-troughism, general lawlessness, and unconstitutionality. As a start, according to Ceci Connolly and R. Jeffrey Smith of the Washington Post, Obama advisers have already been compiling &quot;a list of about 200 Bush administration actions and executive orders that could be swiftly undone to reverse White House policies on climate change, stem cell research, reproductive rights and other issues,&quot; including oil drilling in pristine wild lands. In addition, Obama's people are evidently at work on ways to close Guantanamo and try some of its prisoners in U.S. courts.

However, if continuity domestically means rollback to the Clinton era, continuity in the foreign policy sphere -- Guantanamo aside -- may be a somewhat different matter. We won't know the full cast of characters to come until the president-elect makes the necessary announcements or has a national security press conference with a similar line-up behind him. But it's certainly rumored that Robert Gates, a symbol of continuity from both Bush eras, might be kept on as secretary of defense, or a Republican senator like Richard Lugar of Indiana or, more interestingly, retiring Nebraska Senator Chuck Hagel might be appointed to the post. Of course, many Clintonistas are sure to be in this line-up, too.

In addition, among the essential cast of characters will be Chairman of the Joint Chiefs, Michael Mullen, and Centcom Commander David Petraeus, both late Bush appointees, both seemingly flexible military men, both interested in a military-plus approach to the Afghan and Iraq wars. Petraeus, for instance, reportedly recently asked for, and was denied, permission to meet with Syrian President Bashar al-Assad.

All these figures will represent a turn away from the particular madness of the early Bush years abroad, one that actually began in the final years of his second term. But such a national security line-up is unlikely to include fresh thinkers, who might truly reimagine an imperial world, or anyone who might genuinely buck the power of the Pentagon. What Obama looks to have are custodians and bureaucrats of empire, far more cautious, far more sane, and certainly far more grown-up than the first-term Bush appointees, but not a cast of characters fit for reshaping American policy in a new world of disorder and unraveling economies, not a crew ready to break new ground and cede much old ground on this still American-garrisoned planet of ours.

Breathless in Washington

Let's assume the best: that Barack Obama truly means to bring some form of the people's will, as he imagines it, to Washington after eight years of unconstitutional &quot;commander-in-chief&quot; governance. That -- take my word for it -- he can't do without the people themselves expressing that will.

Of course, even in the Bush era, Americans didn't simply cede the public commons. They turned out, for instance, in staggering numbers to protest the President's invasion of Iraq before it ever happened, and again more recently to work tirelessly to elect Obama president. But -- so it seems to me -- when immediate goals are either disappointingly not achieved, or achieved relatively quickly, most Americans tend to pack their bags and head for home, as so many did in despair after the invasion was launched in 2003, as so many reportedly are doing again, in a far more celebratory mood, now that Obama is elected.

But hard as his election may have been, that was surely the easy part. He is now about to enter the hornet's nest. Entrenched interests. Entrenched ideas. Entrenched ideology. Entrenched profits. Entrenched lobbyists. Entrenched bureaucrats. Entrenched think tanks. An entrenched Pentagon and allied military-industrial complex, both bloated beyond imagining and virtually untouchable, along with a labyrinthine intelligence system of more than 18 agencies, departments, and offices.

Washington remains an imperial capital. How in the world will Barack Obama truly begin to change that without you?

In the Bush years, the special interests, lobbyists, pillagers, and crony corporations not only pitched their tents on the public commons, but with the help of the President's men and women, simply took possession of large hunks of it. That was called &quot;privatization.&quot; Now, as Bush &amp; Co. prepare to leave town in a cloud of catastrophe, the feeding frenzy at the public trough only seems to grow.

It's a natural reaction -- and certainly a commonplace media reaction at the moment -- to want to give Barack Obama a &quot;chance.&quot; Back off those critical comments, people now say. Fair's fair. Give the President-elect a little &quot;breathing space.&quot; After all, the election is barely over, he's not even in office, he hasn't had his first 100 days, and already the criticism has begun.

But those who say this don't understand Washington -- or, in the case of various media figures and pundits, perhaps understand it all too well.

Political Washington is a conspiracy -- in the original sense of the word: &quot;to breathe the same air.&quot; In that sense, there is no air in Washington that isn't stale enough to choke a president. Send Obama there alone, give him that &quot;breathing space,&quot; don't start demanding the quick ending of wars or anything else, and you're not doing him, or the American people, any favors. Quite the opposite, you're consigning him to suffocation.

Leave Obama to them and he'll break your heart. If you do, then blame yourself, not him; but better than blaming anyone, pitch your own tent on the public commons and make some noise. Let him know that Washington's isn't the only consensus around, that Americans really do want our troops to come home, that we actually are looking for &quot;change we can believe in,&quot; which would include a less weaponized, less imperial American world, based on a reinvigorated idea of defense, not aggression, and on the Constitution, not leftover Rumsfeld rules or a bogus Global War on Terror.

Tom Engelhardt, co-founder of the American Empire Project, runs the Nation Institute's TomDispatch.com. He is the author of The End of Victory Culture, a history of the American Age of Denial. The World According to TomDispatch: America in the New Age of Empire (Verso, 2008), a collection of some of the best pieces from his site and an alternative history of the mad Bush years, has recently been published.

[Note for TomDispatch readers: For those who want to follow issues of war and peace, especially in the &quot;arc of instability,&quot; I want to recommend four sites that are sure to prove as invaluable in the Obama era as they have been (to me at least) during the Bush years: Juan Cole's never miss-able Informed Comment blog, AntiWar.com (which has recently added Jason Ditz's useful daily summaries of the latest news developments like this Iraqi one), Paul Woodward's sharp-eyed site The War in Context, and the always fascinating and provocative online newspaper, Asia Times.]

Copyright 2008 Tom Engelhardt




'</recent_news>
    <pic>http://profile.ak.facebook.com/object3/181/101/s31552233595_7304.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object3/181/101/n31552233595_7304.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object3/181/101/t31552233595_7304.jpg</pic_small>
    <creator>571922249</creator>
    <update_time>1230335700</update_time>
    <office/>
    <website/>
    <venue>
      <street/>
    </venue>
    <privacy>OPEN</privacy>
  </group>
  <group>
    <gid>8041227697</gid>
    <name>Warbringer</name>
    <nid>0</nid>
    <description>A group dedicated to the best modern thrash metal band.

An introduction taken from the band's official Myspace:

&quot;Formed in late 2004, we set out to show the local metal scene that Warbringer was a force to be reckoned with, playing no-holds barred rip-roaring thrash metal. After a 4-song demo and tons of shows with fellow ragers like Fueled By Fire, Hirax, Dekapitator, and Toxic Holocaust, we released our &quot;One by One the Wicked Fall&quot; EP in October of 2006. Later that year we had the privilege of signing to one of the most respected heavy metal labels in the world, Century Media.

We recorded our debut album, &quot;War Without End&quot; in the fall of '07 with Mr. Bill Metoyer (Slayer, Fates Warning, Sacred Reich, Hallows Eve) and went on to appear on a few compilation albums such as Speed Kills...Again, Metal For the Masses 6 and the upcoming Thrashing Like a Maniac comp with some of the best acts around like Municipal Waste, Toxic Holocaust and Evile.

Thanks to all of you for your continued support, you guys fucking rule! THRASH OR BE THRASHED, FUCKERS!&quot;

Line up:
John Kevill - Vocals
John Laux - Guitar
Adam Carroll - Guitar
Ben Bennett - Bass
Nic Ritter - Drums

- - - - - -

Other metal related groups that you might want to check out:

Metal Heads
http://www.facebook.com/group.php?gid=5156413654

Minnesota Mayhem: Bringing GOOD Metal to the Masses
http://www.facebook.com/group.php?gid=7849965878

I'm the only person/one of the few people in my school who listens to metal
http://www.facebook.com/group.php?gid=2216954120

The Black Exchange - A Metal Forum
http://www.facebook.com/group.php?gid=20026360676

Lifer : A Metal Forum
http://www.facebook.com/group.php?gid=12575587266</description>
    <group_type>Music</group_type>
    <group_subtype>Metal</group_subtype>
    <recent_news/>
    <pic>http://profile.ak.facebook.com/object2/1286/14/s8041227697_6316.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object2/1286/14/n8041227697_6316.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object2/1286/14/t8041227697_6316.jpg</pic_small>
    <creator>779480695</creator>
    <update_time>1220761029</update_time>
    <office/>
    <website>http://www.myspace.com/warbringer</website>
    <venue>
      <street/>
    </venue>
    <privacy>OPEN</privacy>
  </group>
  <group>
    <gid>43113563593</gid>
    <name>50,000 People to Petetion Prop 8 </name>
    <nid>0</nid>
    <description>We need 50,000 signatures to go against prop 8. even though it already passed we can still go against it and make gay marriage llegal.</description>
    <group_type>Common Interest</group_type>
    <group_subtype>Beliefs &amp; Causes</group_subtype>
    <recent_news>Prop 8 passed. Join the group to petetion.</recent_news>
    <pic/>
    <pic_big/>
    <pic_small/>
    <creator>683757149</creator>
    <update_time>1225933606</update_time>
    <office/>
    <website/>
    <venue>
      <street/>
    </venue>
    <privacy>OPEN</privacy>
  </group>
  <group>
    <gid>25383552676</gid>
    <name>No on Prop 8!!!!</name>
    <nid>0</nid>
    <description>In 2000, Proposition 22 was passed that formally defined marriage could only be between a man and a woman. On May 15, 2008, the California Supreme Court ruled that Prop 22 violated the state constitution of California with a 4-3 vote. And as of June 7, 2008, the state ordered to begin processing same-sex marriages. Over the past two years many groups have been vying for their bill to get on the ballot to ensure that marriage is only legal between a man and a woman. ProtectMarriage.com's bill called the California Marriage Protection Act was able to obtain enough signatures to get on the ballot as Prop 8. If passed, it would add a new section to the California Constitution that would state that only marriage between a man and a woman is valid or recognized in California. If you are 18 or over, please vote no on prop 8. No matter your age, spread the word to those eligible to vote to make sure they are registered to vote and vote no on prop 8. Join this group to ensure that Prop 8 does not pass so that everyone can have equality and feel free to love who they want to. Remember, its about fairness and equality for all.

VOTE NO ON PROP 8!!!!
</description>
    <group_type>Common Interest</group_type>
    <group_subtype>Politics</group_subtype>
    <recent_news>Yes, Prop 8 passed, but that is not the end of this fight. We must petition to get it sent back to the courts and have them make the right decision, the same one that the Californian Supreme Court made earlier this year. We must not give up!! We will repeal Prop 8!!!

---------------------------------------

http://www.msnbc.msn.com/id/21134540/vp/27652443#27652443 

Keith Olbermann makes a really good point about Prop 8.

---------------------------------------

http://www.facebook.com/group.php?gid=43816844487

This is a group that will organize events to help and fight for EQUALITY!! Check out the events and please participate! 

---------------------------------------

In January, there is a scheduled walk in Washington for Gay equality. Please see the link below:

http://www.facebook.com/group.php?gid=45783134257

---------------------------------------

check out &quot;Prop 8: The Musical&quot; a hilarious approach to the situation featuring Jack Black as Jesus!

http://www.funnyordie.com/videos/c0cf508ff8/prop-8-the-musical-starring-jack-black-john-c-reilly-and-many-more-from-fod-team-jack-black-craig-robinson-john-c-reilly-and-rashida-jones
</recent_news>
    <pic>http://profile.ak.facebook.com/object3/848/60/s25383552676_5883.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object3/848/60/n25383552676_5883.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object3/848/60/t25383552676_5883.jpg</pic_small>
    <creator>884795257</creator>
    <update_time>1229319302</update_time>
    <office/>
    <website>http://noonprop8.com/home</website>
    <venue>
      <street/>
    </venue>
    <privacy>OPEN</privacy>
  </group>
  <group>
    <gid>2221373401</gid>
    <name>I Hate Crocs</name>
    <nid>0</nid>
    <description>Hate Crocs? Join the club.

www.IhateCrocs.com</description>
    <group_type>Entertainment &amp; Arts</group_type>
    <group_subtype>Humor</group_subtype>
    <recent_news>We've hit one thousand members. Stay strong, Croc-blockers.

-Vincenzo</recent_news>
    <pic>http://profile.ak.facebook.com/object2/94/98/s2221373401_3592.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object2/94/98/n2221373401_3592.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object2/94/98/t2221373401_3592.jpg</pic_small>
    <creator>771984507</creator>
    <update_time>1208464165</update_time>
    <office/>
    <website>http://www.ihatecrocs.com</website>
    <venue>
      <street/>
    </venue>
    <privacy>OPEN</privacy>
  </group>
  <group>
    <gid>7851891162</gid>
    <name>PEAR</name>
    <nid>0</nid>
    <description>PHP Extension and Application Repository developers and users</description>
    <group_type>Internet &amp; Technology</group_type>
    <group_subtype>Software</group_subtype>
    <recent_news/>
    <pic>http://profile.ak.facebook.com/object2/803/78/s7851891162_269.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object2/803/78/n7851891162_269.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object2/803/78/t7851891162_269.jpg</pic_small>
    <creator>643873853</creator>
    <update_time>1193092743</update_time>
    <office/>
    <website>http://pear.php.net</website>
    <venue>
      <street/>
    </venue>
    <privacy>OPEN</privacy>
  </group>
  <group>
    <gid>26596638803</gid>
    <name>Running, Biking, Swimming</name>
    <nid>67108894</nid>
    <description>RBS is a group for all of us to keep track of the training we're doing. Some of us are training for triathlons while others are training for their summer bikinis.</description>
    <group_type>Sports &amp; Recreation</group_type>
    <group_subtype>Fitness &amp; Exercise</group_subtype>
    <recent_news/>
    <pic/>
    <pic_big/>
    <pic_small/>
    <creator>669245952</creator>
    <update_time>1216403677</update_time>
    <office/>
    <website/>
    <venue>
      <street/>
      <city>San Francisco</city>
      <state>California</state>
      <country>United States</country>
      <latitude>37.775</latitude>
      <longitude>-122.418</longitude>
    </venue>
    <privacy>OPEN</privacy>
  </group>
  <group>
    <gid>2204445195</gid>
    <name>Vim Users</name>
    <nid>0</nid>
    <description>People who use the greatest, most efficient text editor around: vi(m).</description>
    <group_type>Internet &amp; Technology</group_type>
    <group_subtype>Computers &amp; Hardware</group_subtype>
    <recent_news>7/7 - Let there be light.</recent_news>
    <pic>http://profile.ak.facebook.com/object/1611/110/s2204445195_21575.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object/1611/110/n2204445195_21575.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object/1611/110/t2204445195_21575.jpg</pic_small>
    <creator>752944145</creator>
    <update_time>1153161405</update_time>
    <office>Facebook HQ</office>
    <website>http://www.vim.org</website>
    <venue>
      <street/>
    </venue>
    <privacy>OPEN</privacy>
  </group>
  <group>
    <gid>57062945696</gid>
    <name>Order of The Templar</name>
    <nid>0</nid>
    <description>Order of The Templar alumni!</description>
    <group_type>Internet &amp; Technology</group_type>
    <group_subtype>Gaming</group_subtype>
    <recent_news/>
    <pic>http://profile.ak.facebook.com/object3/1073/95/s57062945696_38.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object3/1073/95/n57062945696_38.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object3/1073/95/t57062945696_38.jpg</pic_small>
    <creator>683226814</creator>
    <update_time>1211497141</update_time>
    <office/>
    <website>http://orderofthetemplar.com</website>
    <venue>
      <street/>
    </venue>
    <privacy>CLOSED</privacy>
  </group>
  <group>
    <gid>16688615070</gid>
    <name>NPHS Panther Alumni</name>
    <nid>0</nid>
    <description>This is the ULTIMATE gathering spot for all of Newbury Park High School Alumni from 1968 to present!</description>
    <group_type>Organizations</group_type>
    <group_subtype>Academic Organizations</group_subtype>
    <recent_news>The DECEMBER 2008 Monthly Newsletter is here:

http://www.nphsalumni.com/eNews_12-2008.php</recent_news>
    <pic>http://profile.ak.facebook.com/object3/695/50/s16688615070_729.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object3/695/50/n16688615070_729.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object3/695/50/t16688615070_729.jpg</pic_small>
    <creator>1203424698</creator>
    <update_time>1229010913</update_time>
    <office/>
    <website>http://www.NPHSalumni.com</website>
    <venue>
      <street/>
      <city>Newbury Park</city>
      <state>California</state>
      <country>United States</country>
      <latitude>34.1842</latitude>
      <longitude>-118.91</longitude>
    </venue>
    <privacy>OPEN</privacy>
  </group>
  <group>
    <gid>2739650095</gid>
    <name>Support California High Speed Rail</name>
    <nid>0</nid>
    <description>========================
Thank you all for voting YES on Prop 1A!
California High Speed Rail is on the way.

This group isn't going anywhere, though. We still have a lot of work to do to make sure this gets built, including lobbying Congress to provide the necessary federal funds.
========================
вЂў SF to LA in 2.5 hours
вЂў Trains would travel at 220mph
вЂў SD and Sacramento to be included as well
вЂў Create 450,000 jobs throughout the state (due to economic growth resulting from CAHSR)
вЂў 10,000 fewer auto accidents every year
вЂў Reduce carbon dioxide emissions equivalent to removing 1.4 million cars from the road
вЂў It will pay for its construction cost in the long run
вЂў Reduce CO2 emissions by over 17.6 billion lbs/year
вЂў Reduce CaliforniaвЂ™s oil consumption 22 million barrels/year
вЂў Induce denser development, limiting sprawl
вЂў Half the cost of expanding freeways and airports to meet future travel demand
--------------------------------------------------
High Speed Rail could eliminate the need to construct:
+2970 lane miles of highway
+91 airport gates
+5 additional airport runways
*source: http://www.cahighspeedrail.ca.gov
--------------------------------------------------
WHAT WILL IT COST?
+Estimated total cost of $40 billion USD
&gt;1/3 from a bond measure (meaning taxes will not increase)
&gt;1/3 from state transportation funds
&gt;1/3 from federal transportation matching funds
--------------------------------------------------
WHEN WILL IT BE COMPLETED?
+The first segment from SF to LA to Anaheim in 2014
+The complete system by 2019
+Self-sustaining financially by 2030
--------------------------------------------------
Projected ticket prices can be found here:
http://www.cahighspeedrail.ca.gov/map.htm
=============================
http://www.youtube.com/watch?v=9IZBos3DhZs&amp;NR=1
--------------------------------------------------
Advocacy Group websites:
http://www.highspeedtrainsforca.com/
http://www.goldenstaterail.com
http://www.calhighspeedrail.org/
http://cahsr.blogspot.com/
--------------------------------------------------
Facebook Group: http://www.facebook.com/group.php?gid=2739650095
=================================</description>
    <group_type>Organizations</group_type>
    <group_subtype>Advocacy Organizations</group_subtype>
    <recent_news>ALL ABOARD!!!

Thanks to all of you Proposition 1A passed! We are going to build a modern, safe, sustainable high speed train and bring California into the 21st century.

This group isn't going anywhere though. High speed rail still needs your support. We will have to lobby Congress to provide the necessary funding, and make sure the California legislature provides the support voters demanded in the election.</recent_news>
    <pic>http://profile.ak.facebook.com/object2/57/59/s2739650095_2605.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object2/57/59/n2739650095_2605.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object2/57/59/t2739650095_2605.jpg</pic_small>
    <creator>3326639</creator>
    <update_time>1225982539</update_time>
    <office/>
    <website>http://www.cahighspeedrail.ca.gov</website>
    <venue>
      <street/>
    </venue>
    <privacy>OPEN</privacy>
  </group>
  <group>
    <gid>2225210862</gid>
    <name>DevHouse</name>
    <nid>0</nid>
    <description>SuperHappyDevHouse has become the Bay Area's premier monthly hackathon event that combines serious and not-so-serious productivity with a fun and exciting party atmosphere. Come to the DevHouse to have fun and get things done!

We're about rapid development, ad-hoc collaboration, and cross pollination. Whether you're a l33t hax0r, hardcore coder, or passionate designer, if you enjoy software and technology development, SuperHappyDevHouse was made for you.

DevHouse is not a marketing event. It's a non-exclusive event intended for passionate and creative technical people that want to have some fun, learn new things, and meet new people. In this way, we're trying to resurrect the spirit of the Homebrew Computer Club. We also draw inspiration from the demoscene as one of the only intentional getting-things-done computer events in the world. </description>
    <group_type>Internet &amp; Technology</group_type>
    <group_subtype>Software</group_subtype>
    <recent_news/>
    <pic>http://profile.ak.facebook.com/object/1886/21/s2225210862_32242.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object/1886/21/n2225210862_32242.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object/1886/21/t2225210862_32242.jpg</pic_small>
    <creator>516094870</creator>
    <update_time>1229311554</update_time>
    <office/>
    <website>shdh.org</website>
    <venue>
      <street/>
    </venue>
    <privacy>OPEN</privacy>
  </group>
  <group>
    <gid>7159245548</gid>
    <name>Cubs of Cypress Elementary</name>
    <nid>0</nid>
    <description>Cubs of Cypress Elementary unite! Remember the good old
Halloween event?
Overpriced item sales?
Dodgeball?
FUN PE class?
The old good playground?
Red Ribbon Week?
Monthly Assemblies ?
REALLY REALLY EASY DAYS?
Capture the Flag?
The really nice principal named Mrs. White?
Reading for minipizzas?
How we had 3 breaks, then in the last two years only 2?
Jump Rope for Heart Competitions?
There is always an &quot;in&quot; sport or game?
Some sort of warning system? (red card, yellow, whatever)
The outdoor cafeteria? Right next to the kingdergarten building?
Back to school night was FUN?
Four corners?
We get out at 1:30 for Tuesdays for absolutely no reason?
The fun rainy days?
The &quot;Read so you can take a 10 question test for points&quot; on the computer?
How Sycamore &quot;borrowed&quot; half our campus?

Invite other cubs! Remember the good times! And how we wasted it!</description>
    <group_type>Student Groups</group_type>
    <group_subtype>Alumni Groups</group_subtype>
    <recent_news/>
    <pic>http://profile.ak.facebook.com/object2/639/29/s7159245548_86.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object2/639/29/n7159245548_86.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object2/639/29/t7159245548_86.jpg</pic_small>
    <creator>716461991</creator>
    <update_time>1193107127</update_time>
    <office/>
    <website>http://www.conejo.k12.ca.us/cypress/</website>
    <venue>
      <street/>
      <city>Newbury Park</city>
      <state>California</state>
      <country>United States</country>
      <latitude>34.1842</latitude>
      <longitude>-118.91</longitude>
    </venue>
    <privacy>OPEN</privacy>
  </group>
  <group>
    <gid>8652617682</gid>
    <name>Alyssa's Race for a Cause</name>
    <nid>0</nid>
    <description>Hey everyone! =]

I'm going to be running in the Rock n' Roll marathon in San Diego on June 1st (yes, 26.2 miles! ahh!) and need your help!!! For the next four months I will be training with a group called Team in Training and will be raising funds to help stop leukemia, lymphoma, Hodgkin lymphoma and myeloma from taking more lives. The money I raise will be going towards the cause to help all the individuals who are battling these types of cancer.

In order to run, I have to raise atleast $2,400!!(yikes) Anyone can make a donation to support my participation in Team In Training and help advance the Society's mission. Even if it's just a $5 donation, every little bit will help me!! Below is the link to the personalized website I have created where you can donate online. If it would be easier to send a donation in the mail, let me know and I will give you my address. 

http://www.active.com/donate/tntgla/alyssayas

Thanks so much for your support guys!! =] It means so much to me and just think about how many people you will be helping! 

Love, Alyssa
&lt;3</description>
    <group_type>Organizations</group_type>
    <group_subtype>Philanthropic Organizations</group_subtype>
    <recent_news/>
    <pic>http://profile.ak.facebook.com/object2/1575/13/s8652617682_3330.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object2/1575/13/n8652617682_3330.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object2/1575/13/t8652617682_3330.jpg</pic_small>
    <creator>709260942</creator>
    <update_time>1202247567</update_time>
    <office/>
    <website>http://www.active.com/donate/tntgla/alyssayas</website>
    <venue>
      <street/>
    </venue>
    <privacy>OPEN</privacy>
  </group>
  <group>
    <gid>9689093437</gid>
    <name>Stop segregation</name>
    <nid>0</nid>
    <description>I can...

Adopt a baby
Open a credit card
Go into dept
Loan thousands and thousands of dollars
Have sex
Be in hardcore porn
Marry
Hold political office
Be a legal guardian
Purchase shotguns and revolvers
Enter a contract
Smoke
Buy lottery tickets
Gamble
Own 5 houses
Vote equally with a 45yo
Sue anyone
Decide whither to shoot or not at an iraqies' head
Go be blown up by suicide bombers in Baghdad


But... people do not trust adults under 21 enough to be in art galleries where people alcohol is served.

Or leave a show because I can go back in drunk...just like a 85 year old could do.

Human beings are human beings, adults are adults...adults are no less of a human than adults under 21..

You wouldn't not allow an obese person in the a restaurant because you assume they are not responsible enough to not take all the food.  It would not even cross your mind as it should not cross your mind.

This group is to draw attention to event managers and venue managers that are aware of the segregation, discrimination, and prejudice they create willingly.</description>
    <group_type>Common Interest</group_type>
    <group_subtype>Age</group_subtype>
    <recent_news>Know your candidates.... they sound the issue off by laughing and also they themselves are not responsible  enough to answer a question in 30sec or less... 
http://www.youtube.com/watch?v=1aKCHUfv6ZU&amp;NR=1</recent_news>
    <pic>http://profile.ak.facebook.com/object2/83/27/s9689093437_746.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object2/83/27/n9689093437_746.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object2/83/27/t9689093437_746.jpg</pic_small>
    <creator>683226814</creator>
    <update_time>1202851700</update_time>
    <office/>
    <website/>
    <venue>
      <street/>
    </venue>
    <privacy>OPEN</privacy>
  </group>
  <group>
    <gid>6882266156</gid>
    <name>Spontaneous Drinking Night in SF</name>
    <nid>0</nid>
    <description>As if we needed another excuse to go out drinking mid-week...

Brought to you by Andrew Mager, Cindy Burton, Robert Balousek, Greg Stein and Terry Chay -- it's Spontaneous Drinking Night! Less than a day's notice, some bar in SF, most likely a bunch of geeks, and no agenda other than to have a few beers after work.

We'll send out a FB message when we feel like drinking. Join the group to find out where!

-=Other Spontaneous Drinking Night Groups=-

Panama City: http://www.facebook.com/group.php?gid=5340389927

Silicon Valley: http://www.facebook.com/group.php?gid=19177509584

Vienna:
http://www.facebook.com/group.php?gid=5456637907

Santa Cruz:
http://www.facebook.com/group.php?gid=33024370120

Follow us on Twitter!
http://twitter.com/DrinkingNight

Online:
http://spontaneous.drinkingnight.com</description>
    <group_type>Common Interest</group_type>
    <group_subtype>Gardening</group_subtype>
    <recent_news>6/30 @ 6pm - House of Shields
http://www.new.facebook.com/event.php?eid=24898755926

6/5 @ 6pm - John Colins
http://www.facebook.com/event.php?eid=15658691601

5/30 @ 6pm - Inner Mission Tavern
http://www.facebook.com/event.php?eid=20195647852

5/19 @ 5:30 pm - Kate O'Brien's Irish Bar &amp; Grill
http://www.facebook.com/event.php?eid=14331887961

5/2 @ Noon - Overtime Sports Bar &amp; Grill
http://www.facebook.com/event.php?eid=17090025756

4/30 @ 600pm - House of Shields
http://www.facebook.com/event.php?eid=26751127824

4/9 @ 530pm - John Colins
2/29 @ 6pm - The Chieftain
2/25 @ 545pm - 111 Minna
2/8 @ 530pm - Dave's Bar
1/28 @ 530pm - John Colins
1/20 @ noon - Overtime
12/12 @ 7pm - La Trappe
12/3 @ 7pm - Whiskey Thieves
11/15 @ 6pm - Overtime Bar
11/8 @ 6pm - House of Shields
11/6 @ 6pm - Elixer (16th &amp; Guerrero)
10/24 @ 6pm - House of Shields
</recent_news>
    <pic>http://profile.ak.facebook.com/object3/1445/113/s6882266156_4874.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object3/1445/113/n6882266156_4874.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object3/1445/113/t6882266156_4874.jpg</pic_small>
    <creator>614929601</creator>
    <update_time>1226682648</update_time>
    <office/>
    <website>http://spontaneous.drinkingnight.com/</website>
    <venue>
      <street/>
      <city>San Francisco</city>
      <state>California</state>
      <country>United States</country>
      <latitude>37.775</latitude>
      <longitude>-122.418</longitude>
    </venue>
    <privacy>OPEN</privacy>
  </group>
  <group>
    <gid>2204733559</gid>
    <name>Drinking Age 18</name>
    <nid>0</nid>
    <description>Frustrated with the 21 year-old drinking age?  Want to do something about it?  Then join this group and learn more about our grassroots effort to change a law that has created more problems than it has solved. 

Out of concern for the well-being of young American adults, We embrace this need for change and offer a unique approach to this national problem.  Young adults who choose to drive must be properly taught; so, too, must those who choose to drink. We call for a strategic reform of the laws governing alcoholic beverages.  The reform would permit the lowering of the legal drinking age from 21 to 18 when coupled with a comprehensive program for young adults that requires education and licensing, offers personal incentives for adherence, and enforces responsible and ethical adult behavior among all thoseвЂ”regardless of ageвЂ”who make choices about drinking.


Stay tuned to this discussion group and our website (www.chooseresponsibility.org) for the latest news and updates.  Sign up as a subscriber or volunteer to keep up with [CR] news and updates!


It's time for a change.  Are you ready to make it happen?

Please contact Grace Kronenberg (gkronenberg@chooseresponsibility.org) with questions, comments, or suggestions or if you are interested in learning how you can spread the word on your campus.</description>
    <group_type>Common Interest</group_type>
    <group_subtype>Politics</group_subtype>
    <recent_news/>
    <pic>http://profile.ak.facebook.com/object/1682/68/s2204733559_31536.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object/1682/68/n2204733559_31536.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object/1682/68/t2204733559_31536.jpg</pic_small>
    <creator>0</creator>
    <update_time>1187270653</update_time>
    <office>302 Painter House</office>
    <website>http://www.chooseresponsibility.org/</website>
    <venue>
      <street/>
    </venue>
    <privacy>OPEN</privacy>
  </group>
  <group>
    <gid>9285016286</gid>
    <name>10 Million People to Lower Drinking Age to 18</name>
    <nid>0</nid>
    <description>This is a dispassionate plea to lower the drinking age to 18.  Our tone is not one of liberation but simply reason.  Too many peopleвЂ™s lives have been and are affected by a poor decision to consume a drink containing alcohol while underage.  Some have been expelled from school, others fined, jailed, etc.  The point is that it is an unnecessary drama.  Modern and developed countries all over the world donвЂ™t instigate such policies.  Their kids donвЂ™t drink uncontrollably because they donвЂ™t need to take 21 shots at their birthday.  If people make poor decisions, at any age, they should be held accountable and responsible; but that is a different issue than allowing the consumption of alcohol.  

Whether you drink alcohol or not, this is a chance to change our culture just a little bit.    Some laws are truly abstract.   If we gain enough members we can send this on as a valid issue to consider.  For the first time, the youth might be a strong voting and influential force in politics.  Although this is a state issue, the federal level does have an influence over their policymaking due to various incentive structures created to sway their decisions.  

This is not an intellectual movement, revolution, or rebellion; it is just time for us to catch up to the rest of the world.  We are looking for 10 million members.  Frankly, many of us in the group are 21 and we donвЂ™t care if the law is changed; but enough with the drama of drinking, things need to change.  

I am not advocating alcoholism, or abuse of alcohol, or even social drunkeness.  I am suggesting that there are better means to go about regulating the consumption of alcohol and that the present strategy is not an effective one.  We need other programs that can effect our attitude towards drinking and drinking responsibly.  I don't think we should do away with one regulation without replacing it with another.  

I am looking for an alternative.

Ps.  This is for alcohol only</description>
    <group_type>Common Interest</group_type>
    <group_subtype>Politics</group_subtype>
    <recent_news/>
    <pic>http://profile.ak.facebook.com/object2/759/39/s9285016286_1245.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object2/759/39/n9285016286_1245.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object2/759/39/t9285016286_1245.jpg</pic_small>
    <creator>1476420630</creator>
    <update_time>1226139562</update_time>
    <office/>
    <website>http://www.chooseresponsibility.org/</website>
    <venue>
      <street/>
      <city>New York</city>
      <state>New York</state>
      <country>United States</country>
      <latitude>40.7142</latitude>
      <longitude>-74.0064</longitude>
    </venue>
    <privacy>OPEN</privacy>
  </group>
  <group>
    <gid>2432667580</gid>
    <name>You know you're from Newbury Park When.....</name>
    <nid>0</nid>
    <description>1. Everyone meets up at In-n-Out when the party gets broken up...but then you end up hanging out there for 3 hours cuz there isn't anything else to do.
2. You get pissed off when you see kids from Westlake, Agoura, or TO doing the same thing, cuz this is OUR town!
3. You refer to NP as &quot;The Park&quot;
4. You think it's weird that we have 5 Starbucks within a 3 mile radius (2 in the same shopping center)
5. You were STOKED when Country Harvest came back!
6. You've eaten more pizza from Tony's than any other pizza place
7. You had a crush on one of the girls that worked there
8. You miss Jungle Land
9. FOR THE GIRLS: You got hit on by Fab in APS
10. You had 2 teachers named Mr. Nelson and were convinced one of them was a pot head.
11. Mrs Rait was the best teacher ever!!!
12. It breaks your heart that we used to be a football powerhouse and now we suck! Not to mention some panzy ass private school is number 1 in the country!
13. You talked crap to kids from Moorpark by saying their city name backwards!
14. LOU WAS THE MAN!!!
15. You were told by your history teacher to &quot;stay outta the back of those vans&quot;
16. You hit up Del Taco at 2AM cuz it's the only thing open and nothing beats chicken soft tacos or a bacon double del when you've partied really hard!
17. You can remember what Portrero looked like before Dos...and it sucks!
18. You've been to Akrey's, Take Five, &amp; Azar's...and you don't think twice about it (although maybe you should).
19. You knew that Baja Fresh started in your town!
20. Either your parents or one of your friends' parents work at Amgen
21. If 20 doesn't apply to you replace Amgen with &quot;Fire Department.&quot;
22. You've stood in line behind at least 1 celebrity at Target.
23. You think that the moms that shop at Albertson's are pretty damn hot!
24. You'll take Manny's over Baja any day!
25. You used to ride bikes where the new Albertson's is now
26. And your mom shopped at the old one
27. You've seen or played ball with Will Smith at Borchard park
28. You never take Kanan, Decker, or Malibu Canyon to the beach, it's all about Portrero for you!
29. You or someone you know got their truck stuck in Stacey's Field at least once.
30. You know that the Slaughter House had nothing to do with cows
31. You've partied at &quot;The Circle&quot; more times than you care to count.
32. You don't go to parties in Agoura cuz that's &quot;pretty far&quot;
33. You went through the Janss Mall parking structure when you got your first Flowmaster, just to see how many alarms you could set off.
34. SLAYER!!!!!!!!! ('nuff said)
35. You've ever had to fight at Borchard at 3 o'clock
36. You've ever run a weigh station or a rabbit hill
37. You used to smoke cigarettes off lesser before school until the neighbors made it a No Parking Zone.
38. You got completely wasted at a party at Koenig's...and his mom was there the whole time.
39. It's late August and you find yourself counting the days 'til Langtown
40. You have a counterfeit off-campus pass.
41. Everyone you know that's in a band plays hardcore and you don't know why.
42. You've never taken public transportation.
43.  If you're female: you thought Sapien and Gopher were hot.
44. If you're male: you though Ash was hot until you found out she's a lesbian. Then you thought she was even hotter.
45. You drive the extra 10 minutes to Barnes &amp; Noble instead of Borders cuz you're still bitter that it's not Conejo Bowl anymore.
46. In 12th grade APS you collected clicks instead of points for a job well done.
47. If the same people you graduated high school with were the same kids you went to school with in kindergarten, jr. High, and basically every sport team you ever played on.
48. When your parents were upset about the smoke shop going in the old Albertson's center.
49. When you ate at Big D's
50. You ordered &quot;suicides&quot; from the snack shack
51. When the first place you move out of high school is San Diego
52. You went to your first movie at the Melody theater
53. You remember playing Galaga at Casey's on Kimber road
54. You drove to the top of Mayfield Rd with your favorite gal or guy for the best view of the Conejo Valley
55. You used to go across the street to Thrifty after a game of basketball at Borchard for an ice cream cone
56. You thought the drive to Camarillo on Potrero road was &quot;spooky&quot; because you had to drive past the mental hospital


This is all I got right now, if you've got more send'em to me and I'll add them to the list!</description>
    <group_type>Geography</group_type>
    <group_subtype>Cities</group_subtype>
    <recent_news/>
    <pic/>
    <pic_big/>
    <pic_small/>
    <creator>729950226</creator>
    <update_time>1221004282</update_time>
    <office/>
    <website/>
    <venue>
      <street>456 Reino Rd</street>
    </venue>
    <privacy>OPEN</privacy>
  </group>
  <group>
    <gid>19093242680</gid>
    <name>National 'Go To Class Drunk Day' 2008</name>
    <nid>0</nid>
    <description>Due to the success on November 15th, 2007, the next date has been set up....

Thursday, November 20th, 2008

*The Third Thursday in November of every year is NGTCDD*

Invite your friends. Let's get every School in the country rat assed. Lets have every secondary school student in hospital needing stomach pumps!

This group is for people under 16, and is just for fun. </description>
    <group_type>Just for Fun</group_type>
    <group_subtype>Facebook Classics</group_subtype>
    <recent_news>Free t-shirts: http://www.pleasedress.me/sotd

Funny youtube video: http://www.youtube.com/watch?v=J7wxJOgmu-4</recent_news>
    <pic>http://profile.ak.facebook.com/object2/1334/76/s19093242680_2014.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object2/1334/76/n19093242680_2014.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object2/1334/76/t19093242680_2014.jpg</pic_small>
    <creator>761699755</creator>
    <update_time>1230044737</update_time>
    <office/>
    <website/>
    <venue>
      <street/>
    </venue>
    <privacy>OPEN</privacy>
  </group>
  <group>
    <gid>2216317314</gid>
    <name>F.I.N.A.L.S (Fuck, I Never Actually Learned this Shit)</name>
    <nid>0</nid>
    <description>**********     F.I.N.A.L.S      **********



</description>
    <group_type>Student Groups</group_type>
    <group_subtype>Advising Groups</group_subtype>
    <recent_news>
30 things to do if you are going to fail an exam anyways


1. Get a copy of the exam, run out screaming &quot;Andre, Andre, I've got the secret documents!!&quot;

2. Talk the entire way through the exam. Read questions aloud, debate your answers with yourself out loud. If asked to stop, yell out, &quot;I'm SOOO sure that you can hear me thinking.&quot; Then start talking about what a jerk the instructor is.

3. Bring a Game Boy. Play with the volume at max level.

4. On the answer sheet find a new, interesting way to refuse to answer every question. For example: I refuse to answer this question on the grounds that it conflicts with my religious beliefs. Be creative.

5. Run into the exam room looking about frantically. Breathe a sigh of relief. Go to the instructor, say &quot;They've found me, I have to leave the country&quot; and run off.

6. 15 min. into the exam, stand up, rip up all the papers into very small pieces, throw them into the air and yell out &quot;Merry Christmas.&quot; If you're really daring, ask for another copy of the exam. Say you lost the first one. Repeat this process every 15 min.

7. Come into the exam wearing slippers, a bathrobe, a towel on your head, and nothing else.

8. Come down with a BAD case of Tourette's Syndrome during the exam. Be as vulgar as possible.

9. Bring things to throw at the instructor when s/he's not looking. Blame it on the person nearest to you.

10. As soon as the instructor hands you the exam, eat it.

11. Every 5 min. stand up, collect all your things, move to another seat, continue with the exam.

12. Turn in the exam approx. 30 min. into it. As you walk out, start commenting on how easy it was.

13. Get the exam. 20 min into it, throw your papers down violently, scream out &quot;Fuck this!&quot; and walk out triumphantly.

14. Arrange a protest before the exam starts (ie. Threaten the instructor that whether or not everyone's done, they are all leaving after one hour to go drink.)

15. Show up completely drunk (completely drunk means at some point during the exam, you should start crying for mommy).

16. Comment on how sexy the instructor is looking that day.

17. Come to the exam wearing a black cloak. After about 30 min, put on a white mask and start yelling &quot;I'm here, the phantom of the opera&quot; until they drag you away.

18. If the exam is math/sciences related, make up the longest proofs you could possible think of. Get pi and imaginary numbers into most equations. If it is a written exam, relate everything to your own life story.

19. Try to get people in the room to do a wave.

20. Bring some large, cumbersome, ugly idol. Put it right next to you. Pray to it often. Consider a small sacrifice.

21. During the exam, take apart everything around you. Desks, chairs, anything you can reach.

22. Puke into your exam booklet. Hand it in. Leave.

23. Take 6 packages of rice cakes to the exam. Stuff at least 2 rice cakes into your mouth at once. Chew, then cough. Repeat if necessary.

24. Masturbate.

25. Walk in, get the exam, sit down. About 5 min into it, loudly say to the instructor, &quot;I don't understand ANY of this. I've been to every lecture all semester long! What's the deal? And who the hell are you? Where's the regular guy?&quot;

26. Do the entire exam in another language. If you don't know one, make one up!

27. Bring a black marker. Return the exam with all questions and answers completely blacked out.

28. Every now and then, clap twice rapidly. If the instructor asks why, tell him/her in a very derogatory tone, &quot;the light bulb that goes on above my head when I get an idea is hooked up to a clapper. DUH!&quot;

29. From the moment the exam begins, hum the theme to Jeopardy. Ignore the instructor's requests for you to stop. When they finally get you to leave one way or another, begin whistling the theme to the Bridge on the River Kwai.

30. After you get the exam, call the instructor over, point to any question, ask for the answer. Try to work it out of him/her.

31. In the middle of the test, have a friend rush into the classroom, tag your hand, and resume taking your test for you. When the teacher asks what's going on, calmly explain the rules of Tag Team Testing to him/her.

32. Bring cheat sheets FOR ANOTHER CLASS (make sure this is obvious... like history notes for a calculus exam... otherwise you're not just failing, you're getting kicked out too) and staple them to the exam, with the comment &quot;Please use the attached notes for references as you see fit.&quot;

33. Stand up after about 15 minutes, and say loudly, &quot;Okay, let's double-check our answers! Number one, A. Number two, C. Number three, E....&quot;

34. Fake an orgasm. When interrupted, apologize, and explain that question #__ moved you, deeply.

35. Wear a superman outfit under your normal clothes. 30 minutes into the exam, jump up and answer your phone, shouting &quot;What? I'm on my way!!&quot;. rip off your outer clothes and run out of the room. strike a pose first for added effect.

36. Tailgate outside the classroom before the exam.

37. If your answers are on a scantron sheet, fill it out in pen.

38. Bring a giant cockroach into the room and release it on a girl nearby.</recent_news>
    <pic>http://profile.ak.facebook.com/object3/207/46/s2216317314_6489.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object3/207/46/n2216317314_6489.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object3/207/46/t2216317314_6489.jpg</pic_small>
    <creator>1142040219</creator>
    <update_time>1228284988</update_time>
    <office> http://www.youtube.com/litonevents</office>
    <website>http://www.youtube.com/litonagency</website>
    <venue>
      <street>http://www.youtube.com/litonagency</street>
    </venue>
    <privacy>OPEN</privacy>
  </group>
  <group>
    <gid>2232796441</gid>
    <name>Live It Up Because After College It's Called: Alcoholism</name>
    <nid>0</nid>
    <description>this is for anyone who loves partyinnn n havin fun!!</description>
    <group_type>Just for Fun</group_type>
    <group_subtype>Totally Pointless</group_subtype>
    <recent_news/>
    <pic>http://profile.ak.facebook.com/object/380/23/s2232796441_34447.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object/380/23/n2232796441_34447.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object/380/23/t2232796441_34447.jpg</pic_small>
    <creator>0</creator>
    <update_time>1168317537</update_time>
    <office/>
    <website/>
    <venue>
      <street/>
    </venue>
    <privacy>OPEN</privacy>
  </group>
  <group>
    <gid>2349623590</gid>
    <name>Kevin Rose's New Startup fans.</name>
    <nid>0</nid>
    <description>Well I thought I'd be ahead of the curve and make a group for Kevin Rose, Leah Culver, and Daniel Burka's new startup.

Not many people actually know what it is yet, other than it probably has something to do with IM but I figured if Kevin Rose was behind it, it's gonna have fans.</description>
    <group_type>Internet &amp; Technology</group_type>
    <group_subtype>Websites</group_subtype>
    <recent_news>I can't change the name of the group so I made this group (http://www.facebook.com/group.php?gid=2416034135&amp;ref=mf) 

if you want an invite check out mashable or techcrunch they will probably give some away at some point or find a friend with an acct.</recent_news>
    <pic>http://profile.ak.facebook.com/object2/150/80/s2349623590_2103.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object2/150/80/n2349623590_2103.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object2/150/80/t2349623590_2103.jpg</pic_small>
    <creator>0</creator>
    <update_time>1183022749</update_time>
    <office/>
    <website>http://www.pownce.com/</website>
    <venue>
      <street/>
    </venue>
    <privacy>OPEN</privacy>
  </group>
  <group>
    <gid>2216613701</gid>
    <name> Keep Your Fucking Hand Down in Lecture and Shut Up. No One Cares.</name>
    <nid>0</nid>
    <description>For all those students who want to repeatedly stab themselves in the eye with a sharp object when some moron raises his/her hand to answer a rhetorical question, relate his/her life story, or pointlessly argue with the professor and thereby needlessly prolong class. 

Also welcome are those who want to bludgeon the idiots that walk down to chat with the professor after class, laughing like they are good buddies. Seriously, get a life.

</description>
    <group_type>Common Interest</group_type>
    <group_subtype>Beliefs &amp; Causes</group_subtype>
    <recent_news>http://www.pleasedress.me</recent_news>
    <pic>http://profile.ak.facebook.com/object2/89/57/s2216613701_5483.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object2/89/57/n2216613701_5483.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object2/89/57/t2216613701_5483.jpg</pic_small>
    <creator>541586097</creator>
    <update_time>1227663243</update_time>
    <office/>
    <website/>
    <venue>
      <street/>
    </venue>
    <privacy>OPEN</privacy>
  </group>
  <group>
    <gid>7612746785</gid>
    <name>San Francisco Rock Climbing</name>
    <nid>67108894</nid>
    <description>This group is for people who like to climb rocks.
Introduce yourself - Make new friends - Climb.</description>
    <group_type>Sports &amp; Recreation</group_type>
    <group_subtype>Extreme Sports</group_subtype>
    <recent_news/>
    <pic>http://profile.ak.facebook.com/object2/84/110/s7612746785_6906.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object2/84/110/n7612746785_6906.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object2/84/110/t7612746785_6906.jpg</pic_small>
    <creator>207138</creator>
    <update_time>1218667192</update_time>
    <office/>
    <website/>
    <venue>
      <street/>
    </venue>
    <privacy>OPEN</privacy>
  </group>
  <group>
    <gid>7164051498</gid>
    <name>1,000,000 Strong For Kevin Rose</name>
    <nid>0</nid>
    <description>Hey, Obama has one, Colbert has one, heck even some Canadian guy has one. What's his name again?... Anyway, how can we possibly let the the fad die without engraving Kevin's name in it? Diggers unite! 1,000,000 strong for Kevin Rose!

Primary Image by: Scott Beale / Laughing Squid at www.Laughingsquid.com</description>
    <group_type>Common Interest</group_type>
    <group_subtype>Activities</group_subtype>
    <recent_news>Official start date: November 8th 2007.

Colbert took 9 days. Can we beat that?...</recent_news>
    <pic>http://profile.ak.facebook.com/object2/1386/120/s7164051498_5509.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object2/1386/120/n7164051498_5509.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object2/1386/120/t7164051498_5509.jpg</pic_small>
    <creator>0</creator>
    <update_time>1194498479</update_time>
    <office/>
    <website>http://kevinrose.com</website>
    <venue>
      <street>Digg Street</street>
      <city>San Francisco</city>
      <state>California</state>
      <country>United States</country>
      <latitude>37.775</latitude>
      <longitude>-122.418</longitude>
    </venue>
    <privacy>OPEN</privacy>
  </group>
  <group>
    <gid>5835507439</gid>
    <name>280 is the Most Ballin Freeway in the World</name>
    <nid>0</nid>
    <description>Have you ever mobbed down 280?  If not, snatch yourself a whip and roll out to the Bay, and see for yourself that 280 is, indeed, the most crackin piece of asphalt anywhere on the planet.

By the way, if you want to be an officer, message me with your preferred title and I'll slap you on the list.

Member Count:
-------------------
11/08/2007 - Started
11/09/2007 - 100 members
11/11/2007 - 200 members
11/21/2007 - 300 members
12/03/2007 - 400 members
1/23/2008 - I found something better to do than count the number of people in this group.</description>
    <group_type>Common Interest</group_type>
    <group_subtype>Philosophy</group_subtype>
    <recent_news>5/26/2008 - It's summer bitches.  Now we mob the 280 all day and all night, yadadafeelme? If you see a red prelude shootin by at a hundo plus, holla at yo boy.

1/23/2008 - 280 is still ridiculous.  Props to all the photo and wall contributors.

11/09/2007 - I'm glad to see that so many people have joined.  I know all of us away at college long for the gentler curves and wider lanes of 280, whether or not we've been driving.  There's just nothing that can fill the void in our hearts.

11/17/07 - Oh baby. I rolled 280 all the way down to 17 (Santa Cruz) today.  It was bliss.

12/13/07 - I hope to see all of you on 280 over winter break. </recent_news>
    <pic>http://profile.ak.facebook.com/object2/415/62/s5835507439_523.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object2/415/62/n5835507439_523.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object2/415/62/t5835507439_523.jpg</pic_small>
    <creator>726647909</creator>
    <update_time>1211856398</update_time>
    <office>280</office>
    <website>http://280.is.ballin/</website>
    <venue>
      <street>280 BITCH</street>
      <city>Menlo Park</city>
      <state>California</state>
      <country>United States</country>
      <latitude>37.4542</latitude>
      <longitude>-122.179</longitude>
    </venue>
    <privacy>OPEN</privacy>
  </group>
  <group>
    <gid>7046515989</gid>
    <name>Mahalo News</name>
    <nid>0</nid>
    <description>Follow the latest breaking news along with Mahalo Guides!</description>
    <group_type>Internet &amp; Technology</group_type>
    <group_subtype>News</group_subtype>
    <recent_news/>
    <pic>http://profile.ak.facebook.com/object2/1821/74/s7046515989_81.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object2/1821/74/n7046515989_81.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object2/1821/74/t7046515989_81.jpg</pic_small>
    <creator>533519803</creator>
    <update_time>1194559034</update_time>
    <office/>
    <website>http://www.mahalo.com/Category:News</website>
    <venue>
      <street/>
    </venue>
    <privacy>OPEN</privacy>
  </group>
  <group>
    <gid>5895388713</gid>
    <name>SNAP Summit</name>
    <nid>0</nid>
    <description>A series of events discussing the development, virality, and monetization of social network application platforms (SNAPs).</description>
    <group_type>Internet &amp; Technology</group_type>
    <group_subtype>Websites</group_subtype>
    <recent_news>The folks that bring you SNAP Summit have put together a new series of conferences focusing on successful advertising techniques on social networks.  The first SWAT Summit will take place in downtown San Francisco on July 17th, and is slated to bring together some of the biggest California agencies, brands, social networks, and media buyers, and publishers active in the field.

For more information, head to http://www.swatsummit.com
Pick up tickets at:
http://swatsummit.eventbrite.com
*************************************************

It's coming.  SNAP Summit ^3.  Tentatively save the date: October 15th, 2008.</recent_news>
    <pic>http://profile.ak.facebook.com/object3/280/24/s5895388713_7399.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object3/280/24/n5895388713_7399.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object3/280/24/t5895388713_7399.jpg</pic_small>
    <creator>3324802</creator>
    <update_time>1216930481</update_time>
    <office/>
    <website>http://www.snapsummit.com</website>
    <venue>
      <street>700 Howard St.</street>
      <city>San Francisco</city>
      <state>California</state>
      <country>United States</country>
      <latitude>37.775</latitude>
      <longitude>-122.418</longitude>
    </venue>
    <privacy>OPEN</privacy>
  </group>
  <group>
    <gid>7406420086</gid>
    <name>1,000,000 Strong For Stephen T Colbert</name>
    <nid>0</nid>
    <description>Join this group if you love watching The Colbert Report!

Guests this week:

Jan 12: Anthony Romero
Executive Director, ACLU

Jan 13: Niall Ferguson
Author, &quot;The Ascent of Money: The Financial History of the World&quot;

Jan 14: Alan Khazei
Founder and CEO, Be the Change, Inc.

Jan 15: David Gregory
Moderator, &quot;Meet the Press&quot;
 
++++++++++++++++++++++++++++++++++++++++++++++++++

Colbert Quotes:

&quot;We know that polls are just a collection of statistics that reflect what people are thinking in 'reality.' And reality has a well known liberal bias.&quot;

&quot;No animals were harmed in the recording of this episode. We tried but that damn monkey was just too fast.&quot;

&quot;Folks, the President needs a break. He's like a Black and Decker cordless Dirt Devil vacuum. If you donвЂ™t recharge his batteries, he can't suck.&quot;

&quot;Democrats lead in all the polls by at least ten points, except one... Fox News. That is within a margin of error of plus-or-minus the facts.

&quot;Now it seems the crooks charge twenty to thirty thousand dollars for a fake Ferrari body attached to an old Pontiac chassis, and here's how you sniff out a fake: take a pocketknife and scrape off some of the paint on the hood just behind the ornament. If it's a real Ferrari... someone will kick your ass.&quot;</description>
    <group_type>Entertainment &amp; Arts</group_type>
    <group_subtype>Celebrities</group_subtype>
    <recent_news>10,000 Members at 10:22 PM (EST) 10/17/2007
50,000 Members at 4:48 PM (EST) 10/18/2007
100,000 Members at 12:54 AM (EST) 10/19/2007
170,000 Members at 4:58 PM (EST) 10/19/2007
200,000 Members at 9:43 PM (EST) 10/19/2007 
250,000 Members at 11:58 AM (EST) 10/20/2007
300,000 Members at 7:44 PM (EST) 10/20/2007
350,000 Members at 4:43 AM (EST) 10/21/2007
400,000 members at 5:11 PM (EST) 10/21/2007
450,000 Members at 12:16 PM (EST) 10/22/2007
500,000 Members at 5:11 AM (EST) 10/23/2007
550,000 Members at 3:59 PM (EST) 10/23/2007
600,000 Members at 8:06 PM (EST) 10/23/2007
650,000 Members at 11:24 PM (EST) 10/23/2007
700,000 Members at 11:34 AM (EST) 10/24/2007
750,000 Members at 5:31 PM (EST)  10/24/2007
800,000 Members at 9:40 PM (EST) 10/24/2007 
850,000 members at 4:22 AM (EST) 10/25/2007
900,000 Members at 3:24 PM (EST) 10/25/2007
950,000 Members at 9:11 PM (EST) 10/25/2007
1,000,000 Members at 5:54 AM (EST) 10/26/2007
1,100,000 Members at 3:06 PM (EST) 10/27/2007
1,200,000 Members at 6:01 AM (EST) 10/29/2007
1,300,000 Members at 1:36 PM (EST) 10/31/2007
1,400,000 Members at 2:12 PM (EST) 11/4/2007
1,500,000 Members at 1:12 AM (EST) 11/21/2007
</recent_news>
    <pic>http://profile.ak.facebook.com/object3/1170/61/s7406420086_6974.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object3/1170/61/n7406420086_6974.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object3/1170/61/t7406420086_6974.jpg</pic_small>
    <creator>33610583</creator>
    <update_time>1231795941</update_time>
    <office/>
    <website/>
    <venue>
      <street/>
    </venue>
    <privacy>OPEN</privacy>
  </group>
  <group>
    <gid>2437497388</gid>
    <name>tips for all women... please join... and pass on</name>
    <nid>0</nid>
    <description>Pass this to everyone you know and love... expecially the women... dont let one of your friends end up on the news...
</description>
    <group_type>Common Interest</group_type>
    <group_subtype>Beliefs &amp; Causes</group_subtype>
    <recent_news>Everyone should take 5 minutes to read this. It may save your life or
a loved one's life.

so i was given some great advice, and wanted to alter the name slightly to encourage men to join as well, but it wont let me... so please invite all the men you know! thanks


Crucial
Because of recent abductions
in daylight hours,refresh yourself
of these things to do
in an emergency situation...
This is for you,
and for you to share
with your wife,
your children,
everyone you know.


After reading these 9 crucial tips ,
forward them to someone you care about.
It never hurts to be careful
in this crazy world we live in.

Tip #1. Tip from Tae Kwon Do :
The elbow
is the strongest point
on your body.
If you are close enough to use it,
do!

Tip # 2. Learned this from a tourist guide
in New Orleans
If a robber asks
for your wallet and/or purse,
DO NOT HAND IT TO HIM
Toss it away from you....
chances are
that he is more interested
in your wallet and/or purse
than you,
and he will go
for the wallet/purse.
RUN LIKE MAD IN THE OTHER DIRECTION!


Tip # 3. If you are ever thrown
into the trunk of a car,
kick out the back tail lights
and stick your arm out the hole
and start waving like crazy.
The driver won't see you,
but everybody else will.
This has saved lives.


Tip # 4. Women have a tendency
to get into their cars after shopping,
eating, working, etc.,
and just sit (doing their checkbook,
or making a list, etc.
DON'T DO THIS!)
The predator
will be watching you,
and this is the perfect opportunity
for him to get in
on the passenger side,
put a gun to your head,
and tell you where to go.
AS SOON AS YOU GET INTO YOUR CAR ,
LOCK THE DOORS AND LEAVE.

and... If someone
is in the car
with a gun
to your head
DO NOT DRIVE OFF,
repeat:
DO NOT DRIVE OFF!
Instead gun the engine
and speed into anything,
wrecking the car.
Your Air Bag will save you.
If the person is
in the back seat
they will get the worst of it .
As soon as the car crashes
bail out and run.
It is better than having them
find your body
in a remote location.

Tip # 5. A few notes about getting
into your car in a parking lot,
or parking garage:
A..) Be aware:
look around you,
look into your car,
at the passenger side floor ,
and in the back seat
B..) If you are parked next to a big van,
enter your car from the passenger door .
Most serial killers attack their victims
by pulling them into their vans
while the women are attempting
to get into their cars.
C..) Look at the car
parked on the driver's side
of your vehicle,
and the passenger side.
If a male is sitting alone
in the seat nearest your car,
you may want to walk back
into the mall, or work,
and get a guard/policeman
to walk you back out.
IT IS ALWAYS BETTER TO BE SAFE THAN SORRY. (And better paranoid than
dead.)

Another tip i was resently given concerning cars, if you are ever locked into a car and the keys are in the ignition obviously drive that van away from there... fast! BUT if no keys are in the ignition jam it with something like a boby pin (break it in there) or even a wad of chewing gum... remember, leaving the primary location is the worst situation possible, if he cant start his van... he is going to have a much harder time transporting you. 


Tip #6. ALWAYS
take the elevator
instead of the stairs.
(Stairwells are horrible places
to be alone
and the perfect crime spot.
This is especially true at NIGHT!)

Tip # 7. If the predator has a gun
and you are not under his control,
ALWAYS RUN!
The predator will only hit you
(a running target)
4 in 100 times;
And even then,
it most likely
WILL NOT
be a vital organ.
RUN,
Preferably, in a zig -zag pattern! (This was confirmed in the K.C. Star)


Tip # 8. As women,
we are always trying
to be sympathetic:
STOP !
It may get you raped,
or killed.
Ted Bundy,
the serial killer,
was a good-looking,
well educated man,
who ALWAYS played
on the sympathies
of unsuspecting women.
He walked with a cane,
or a limp,
and often asked
&quot;for help&quot;
into his vehicle
or with his vehicle,
which is when he abducted
his next victim.
************* Here it is *******
Tip # 9. Another Safety Point:
Someone just told me
that her friend heard
a crying baby on her porch
the night before last,
and she called the police
because it was late
and she thought it was weird.
The police told her
&quot;Whatever you do,
DO NOT
open the door.&quot;
The lady
then said that
it sounded like the baby
had crawled near a window,
and she was worried
that it would crawl
to the street
and get run over.
The policeman said,
&quot;We already have a unit on the way,
whatever you do,
DO NOT open the door.&quot;
He told her that they think
a serial killer
has a baby's cry recorded
and uses it to coax
women out of their homes
thinking that someone
dropped off a baby
He said they have not verified it,
but have had several calls
by women saying that
they hear baby's cries
outside their doors
when they're home alone
at night.

Please pass this on and
DO NOT
open the door
for a crying baby ----
This
e-mail should probably
be taken seriously because
the Crying Baby theory
was mentioned on
America 's Most Wanted
this past Saturday
when they profiled
the serial killer in Louisiana

another tip i was sent earlier:
Know how much money you take into stores/gas stations.
It is a new trend that preditors will see somone get into their car, and knock on their window. Then they will show them a $5 or $10 bill and tell them they dropped it on the way back to their car. After the woman thanks the kind man, and opens her door, he will grab her. It almost happened to one woman, but she knew she only too $5 with her into the gas station, so the change was not hers, another woman got raped by that man though.

also, if you are ever pulled over and arnt sure if the &quot;cop&quot; pulling you over is really a cop you can call 911, do not roll down your window untill other poliece men arrive at the scene... the poliece man who pulled you over should understand this as it is part of their training.


I'd like you
to forward this
to all the women you know.
It may save a life.
A candle is not dimmed
by lighting another candle.
I was going to send this to the ladies only,
but guys,
if you love your mothers,
wives,
sisters,
daughters, etc.,
you may want to
pass it onto them, as well.

Send this
to any woman you know
that may need
to be reminded
that the world we live in
has a lot of crazies in it
and it's better to be safe
than sorry.



Everyone should take 5 minutes to read this. It may save your life or
a
loved one's life.</recent_news>
    <pic>http://profile.ak.facebook.com/object2/1495/56/s2437497388_9770.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object2/1495/56/n2437497388_9770.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object2/1495/56/t2437497388_9770.jpg</pic_small>
    <creator>679328331</creator>
    <update_time>1193783724</update_time>
    <office/>
    <website/>
    <venue>
      <street/>
    </venue>
    <privacy>OPEN</privacy>
  </group>
  <group>
    <gid>2232565578</gid>
    <name>I CAN HAS CHEEZBURGER?</name>
    <nid>0</nid>
    <description>I CAN HAS CHEEZBURGER?
No, you can't has cheezburger.
I HAS A CHEEZBURGER
Canst thine hath milk curd and minced beef sandwich?
No, thou canst not hath milk curd and minced beef sandwich.

GROUP-WIDE NOTE
Wanna get political? How about immediate-banishment political? Political images will be removed and the posters will be banned on sight. Please report any images that violate this rule and I will take action as soon as I can.</description>
    <group_type>Just for Fun</group_type>
    <group_subtype>Totally Random</group_subtype>
    <recent_news>we's at 441 mimbars!!! inviet moar!!

615!! omGG!!

::may 24-2007::
708 membrz.
i has explodeded.

::may30-2007::
879 membrz
YUSS!!

::june5-2007::
1025 MMBRZ!!
WIIZ BREYK AFOUZND!!

::june15-2007::
1,330
Ceiling cat is watch your group get bigger.

::july25-2007::
OMG!!1
HAI2 ALLYUU!!
oavr 2000 membrz!!

::october15-2007::
JEEZIS KRUYSTT!!
ovar 3000 pplz.

::march24-2008::
IMMA CHARGIN MAH FACEBOOK GROUPS
breached 5000 membrz. well-done lollernauts.

::april24-2008::
This was a triumph...
6,150 and counting. Good job, test subjects. Invite more and there will be delicious cake for you.

::october24-2008::
7,114. Seven thousand, one hundred, and fourteen people loading images into a web app, destroying grammar and convention in a singular act of cutesy defiance. We gave the textbook the middle finger and began the throwdown of the century.

Shouldn't you all be wearing helmets or something?

I am.</recent_news>
    <pic>http://profile.ak.facebook.com/object/1304/45/s2232565578_36630.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object/1304/45/n2232565578_36630.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object/1304/45/t2232565578_36630.jpg</pic_small>
    <creator>768607021</creator>
    <update_time>1224901475</update_time>
    <office/>
    <website>http://www.icanhascheezburger.com/</website>
    <venue>
      <street/>
    </venue>
    <privacy>OPEN</privacy>
  </group>
  <group>
    <gid>11770480704</gid>
    <name>The GigaOM Show Fans</name>
    <nid>0</nid>
    <description>This group is dedicated to fans of the new show hosted by Om Malik from GigaOm and Joyce Kim. 

About the Show:
&quot;The GigaOm Show, hosted by Om Malik and Joyce Kim, is a weekly show about technology and business. Om and Joyce serve up the inside analysis of technology news that you need to know. Each week, the dynamic duo will preview a hot startup and get to know a seasoned entrepreneurial vet.&quot; - REV3</description>
    <group_type>Internet &amp; Technology</group_type>
    <group_subtype>Cyberculture</group_subtype>
    <recent_news/>
    <pic>http://profile.ak.facebook.com/object2/533/74/s11770480704_1914.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object2/533/74/n11770480704_1914.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object2/533/74/t11770480704_1914.jpg</pic_small>
    <creator>5136658</creator>
    <update_time>1186363833</update_time>
    <office/>
    <website>http://revision3.com/gigaom/</website>
    <venue>
      <street/>
    </venue>
    <privacy>OPEN</privacy>
  </group>
  <group>
    <gid>10142325116</gid>
    <name>RIP Ryan Edwards</name>
    <nid>0</nid>
    <description>It seems simplistic to try and commemorate a life with something as simplistic and invalueable as a facebook group, especially when that life belongs to someone as moving and inspirational as Ryan Edwards. Anyone who was close to Ryan or has even been around him for more than 3 minutes knows that he had the strangest way of being able to put a smile on your face with virtually know trouble. He was also one of the deepest and most intelectual kids i know. He could twist your mind with his thoughts, his wits and his provacotive statements. But how do we celebrate a life so short lived? A facebook group is certainly no way to remember somebody so giant in personality. I for one can't remember the last things i said to Ryan, i do know however, that it wasnt enough. I was reasured in my human ignorance that i would simply see him &quot;next time he came down&quot;. So perhaps in his honor we should tell everyone we care about every day we love them. 

When i first heard of the accident i cried shakily for about an hour. Thinking of Ryan's cold alone body, Leslie tears, Mike's sobbs, and as Justin reminded me, his children that would now grow up without an uncle. All wanted to do was wrap my arms around Leslie, tell Justin he still had a brother in me and Ray, and let Mike know he made the best of choices in raising his sometimes troubled son. Then i began to think about if your life really does flash before your eyes, what did Ryan see? Surely he saw Michael Becker and Chris Paine at one of there many parties. Surely he saw Ally, Danielle, and tyler surfing on the east coast of his home state. And surely, above all, did he see the adoring face of what i know to be his only true love, Kelly Stagi. 

Imagine someone of vital importance in your life being snatched away and cut off forever. Now imagine not telling everybody else of vital importance in your life you love them the next day. I know its what Ryan wants us to do. In fact, i can almost feel him watching us from some beach in heaven, drinkin a beer and thinking about the times he used to have with the people he left behind.

I love you ryan, you were the closest thing to an older brother i ever had. </description>
    <group_type>Common Interest</group_type>
    <group_subtype>Friends</group_subtype>
    <recent_news/>
    <pic>http://profile.ak.facebook.com/object2/1855/123/s10142325116_8683.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object2/1855/123/n10142325116_8683.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object2/1855/123/t10142325116_8683.jpg</pic_small>
    <creator>542596130</creator>
    <update_time>1188250934</update_time>
    <office/>
    <website/>
    <venue>
      <street/>
    </venue>
    <privacy>OPEN</privacy>
  </group>
  <group>
    <gid>4730564673</gid>
    <name>Vinfolio</name>
    <nid>0</nid>
    <description>A place for all Vinfolio customers and VinCellar online cellar management software users to interact.</description>
    <group_type>Common Interest</group_type>
    <group_subtype>Wine</group_subtype>
    <recent_news>Our My VinCellar facebook widget is ready for use.  Check it out and post any feedback on the My VinCellar page in Facebook.

Check out latest release notes in Vinfolio forums (under Community tab on home page).  10-5-07.

Vinfolio raised $4.5 million in financing in August 2007.</recent_news>
    <pic>http://profile.ak.facebook.com/object2/1933/95/s4730564673_6649.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object2/1933/95/n4730564673_6649.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object2/1933/95/t4730564673_6649.jpg</pic_small>
    <creator>520021154</creator>
    <update_time>1231914268</update_time>
    <office>Vinfolio, Inc.</office>
    <website>www.vinfolio.com</website>
    <venue>
      <street>1890 Bryant St., Suite 208</street>
      <city>San Francisco</city>
      <state>California</state>
      <country>United States</country>
      <latitude>37.775</latitude>
      <longitude>-122.418</longitude>
    </venue>
    <privacy>OPEN</privacy>
  </group>
  <group>
    <gid>2321424783</gid>
    <name>Official Totally Rad Show Group</name>
    <nid>0</nid>
    <description>Welcome To The Official Totally Rad Show Facebook Group!

Links:
	
http://www.digg.com/podcasts/The_Totally_Rad_Show

http://www.myspace.com/totallyradshow

http://www.jinx.com/trs
</description>
    <group_type>Internet &amp; Technology</group_type>
    <group_subtype>Cyberculture</group_subtype>
    <recent_news>Episode #70 is out now, watch it here:

http://revision3.com/trs/guess/

It's OFFICIAL - TRS Live Show at Comicon - Friday July 25 7:15pm 

This has been a long time in coming, but we finally got the word from the Comicon peeps. We got an evening panel slot and we're going to be doing a full-on live show.

If you were thinking at all about joining the big dance in San Diego, please take the plunge. We'd love to have a big crowd of forum folks. 

It should be a blast. We'll be shooting an episode, giving some stuff away, and having an extended Q&amp;A at the end.

Hope to see you there!

The deets:

Totally Rad Show @ San Diego Comicon.
Friday, JULY 25
7:15-9:15pm

Rev3 has a new facebook app and a new fan page! Support Rev3 shows like the Totally Rad Show by adding the Official Rev3 App to your page and becoming a fan of the show! Here are the links:

http://apps.facebook.com/revthreeplayer/

http://www.facebook.com/profile.php?id=6205753201

-Alex, Dan, Jeff &amp; Steve

ps Please help support TRS and subscribe on ITUNES, then unsubscribe and then RE-subscribe! </recent_news>
    <pic>http://profile.ak.facebook.com/object2/1841/113/s2321424783_292.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object2/1841/113/n2321424783_292.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object2/1841/113/t2321424783_292.jpg</pic_small>
    <creator>519774135</creator>
    <update_time>1216754932</update_time>
    <office>The Internets</office>
    <website>www.totallyradshow.com</website>
    <venue>
      <street/>
    </venue>
    <privacy>OPEN</privacy>
  </group>
  <group>
    <gid>3335308636</gid>
    <name>Dumbledore's Army</name>
    <nid>0</nid>
    <description>Wizards and Witches commited to fighting the dark lord Voldemort on behalf of Albus Dumbledore.</description>
    <group_type>Common Interest</group_type>
    <group_subtype>Beliefs &amp; Causes</group_subtype>
    <recent_news>http://www.darkarts-defence.com/uk/home.aspx = bad game/good idea

July 31, 2007                                                   100 members
October 25, 2007                                         16500 members
December 14, 2007                                       17750

Its offical, on August 06, 2007 Dumbledore's Army gained 1000 new members in Once Day! Party!

http://apps.facebook.com/vampires/install.php?infecter=1155513183

http://apps.facebook.com/jedi_vs_sith/invited.php?ui=1155513183&amp;side=2


I would like to thank Maggie Austin of the the Facebook club &quot;NOT MY DAUGHTER, YOU BITCH!&quot; : Mrs. Weasley Appreciation Group for making our club the official Dumbledore's Army of the site. Since the edition of DA on the club site, membership has increased ten fold, and in the spirit of this I announce, Maggie Austin, you have only to ask and I shall bear your children.

Remember to invite your friends!!!

Dumbledore is Gay = http://www6.comcast.net/news/articles/entertainment/2007/10/20/Books.Harry.Potter/

OUR BROTHER AND SISTER ORGANIZATIONS
&quot;NOT MY DAUGHTER, YOU BITCH!&quot; : Mrs. Weasley Appreciation Group
http://hs.facebook.com/group.php?gid=2600267260
Why Are You Worrying About You-Know-Who?
http://hs.facebook.com/group.php?gid=4736192953
People Think I need help because of my Harry Potter Obsession
http://hs.facebook.com/group.php?gid=6720247773
The Remus Lupin and Nymphadora Tonks Memorial
http://hs.facebook.com/group.php?gid=2434358338
Here Lies Dobby, A Free Elf
http://hs.facebook.com/group.php?gid=2435453142
the order members,D.A., with the death eaters
http://www.facebook.com/group.php?gid=5095904798
Fuck this...I'm going to Hogwarts
http://hs.facebook.com/group.php?gid=2204884081
Albus Dumbledore Support Group
http://hs.facebook.com/group.php?gid=5698662407
Fred Weasley, We Salute You
http://hs.facebook.com/group.php?gid=4157460047
Dumbledore, The Wise GAYlord
http://hs.facebook.com/group.php?gid=5644633004
Harry Potter vs the Lord of the Rings -- The Count to One Million
http://hs.facebook.com/group.php?gid=8371910029

http://www.msnbc.msn.com/id/19959323/


redoing all the officers
whoever looks most like the character they want to be should post a picture of them
if your officer title is Ron Weasley then u better have red hair and some robes on </recent_news>
    <pic>http://profile.ak.facebook.com/object2/945/98/s3335308636_8159.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object2/945/98/n3335308636_8159.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object2/945/98/t3335308636_8159.jpg</pic_small>
    <creator>888475474</creator>
    <update_time>1197863858</update_time>
    <office>Hogwarts School of Witchcraft and Wizardry</office>
    <website/>
    <venue>
      <street>Somewhere in England</street>
    </venue>
    <privacy>OPEN</privacy>
  </group>
  <group>
    <gid>5048667073</gid>
    <name>R.I.P. Ryan Edwards</name>
    <nid>0</nid>
    <description>For anyone who knew Ryan, an amazing person who is, and will be loved forever. You were the most fun person anyone ever met and you always knew how to make everyone laugh. You put a smile on everyones face. You were always the life of the party... those parties just wont be the same without you. You will never be forgotten.

You will always be in our hearts.

Please feel free to post any and all pictures you have of Ryan.

Our hearts go out to Ryan's family.

We miss you man.</description>
    <group_type>Student Groups</group_type>
    <group_subtype>General</group_subtype>
    <recent_news>By Adam Foxman (Contact) 
Originally published 12:12 p.m., August 27, 2007
Updated 01:36 p.m., August 27, 2007 

Authorities identified this afternoon the 18-year-old Newbury Park man who was killed in an accident in Thousand Oaks early this morning.

Ryan Edwards, 18, was a passenger in a car that rolled over, according to Chief Deputy Ventura County Medical Examiner James Baroni.

The driver was arrested on suspicion of driving under the influence, the California Highway Patrol said.

Edwards and two other teens were returning from Tijuana when the vehicle crashed about 2:10 a.m. on Highway 101 near the Highway 23 interchange, CHP Officer Randy Pickens said.

The driver, a 17-year-old male from Thousand Oaks, was traveling north on Highway 101 just south of Rancho Road when he made an unsafe turn, collided with a sign, went down an embankment to Highway 23 and turned over, the CHP reported.

Edwards was partially ejected and killed when the vehicle rolled, Pickens said. He was not wearing a seatbelt.

He suffered multiple blunt force injuries and died at the scene, said Chief Deputy Ventura County Medical Examiner James Baroni.</recent_news>
    <pic>http://profile.ak.facebook.com/object2/598/8/s5048667073_813.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object2/598/8/n5048667073_813.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object2/598/8/t5048667073_813.jpg</pic_small>
    <creator>1047480108</creator>
    <update_time>1188283512</update_time>
    <office/>
    <website/>
    <venue>
      <street/>
    </venue>
    <privacy>OPEN</privacy>
  </group>
  <group>
    <gid>2416034135</gid>
    <name>Pownce</name>
    <nid>0</nid>
    <description>This is the semi-official Pownce Group because Kevin joined.</description>
    <group_type>Internet &amp; Technology</group_type>
    <group_subtype>Websites</group_subtype>
    <recent_news>It launched
I have an acct. but no invites, sorry. 

http://www.pownce.com/ben
</recent_news>
    <pic>http://profile.ak.facebook.com/object2/1567/10/s2416034135_2225.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object2/1567/10/n2416034135_2225.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object2/1567/10/t2416034135_2225.jpg</pic_small>
    <creator>570406071</creator>
    <update_time>1185695389</update_time>
    <office/>
    <website>http://www.pownce.com</website>
    <venue>
      <street/>
    </venue>
    <privacy>OPEN</privacy>
  </group>
  <group>
    <gid>2264338778</gid>
    <name>Official Diggnation Group</name>
    <nid>0</nid>
    <description>Welcome To The Official Diggnation Facebook Group!

Hi All -

We created this group to keep you informed of live show announcements, general diggnation news, and bar meetups.

Digg on,

- Kevin &amp; Alex

Kevin on FB:
http://www.facebook.com/profile.php?id=6162642477

Alex on FB:
http://www.facebook.com/profile.php?id=6200204789
</description>
    <group_type>Internet &amp; Technology</group_type>
    <group_subtype>Websites</group_subtype>
    <recent_news/>
    <pic>http://profile.ak.facebook.com/object/1752/63/s2264338778_37243.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object/1752/63/n2264338778_37243.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object/1752/63/t2264338778_37243.jpg</pic_small>
    <creator>809090322</creator>
    <update_time>1195856607</update_time>
    <office>San Francisco</office>
    <website>http://www.diggnation.com</website>
    <venue>
      <street/>
    </venue>
    <privacy>OPEN</privacy>
  </group>
  <group>
    <gid>2234436064</gid>
    <name>I go/went to Newbury Park High</name>
    <nid>0</nid>
    <description>Anyone who goes to NPHS or alumni.... Invite whoever you want!

Add any pics you have from nphs! =)</description>
    <group_type>Just for Fun</group_type>
    <group_subtype>Totally Random</group_subtype>
    <recent_news/>
    <pic>http://profile.ak.facebook.com/object/1464/84/s2234436064_31416.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object/1464/84/n2234436064_31416.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object/1464/84/t2234436064_31416.jpg</pic_small>
    <creator>1047480108</creator>
    <update_time>1177831925</update_time>
    <office/>
    <website/>
    <venue>
      <street/>
    </venue>
    <privacy>OPEN</privacy>
  </group>
  <group>
    <gid>2235780222</gid>
    <name>NPHS class of '07</name>
    <nid>0</nid>
    <description>this is for all of us graduating from Newbury Park High School in 2007....go us!</description>
    <group_type>Student Groups</group_type>
    <group_subtype>Alumni Groups</group_subtype>
    <recent_news>second semester of senior year!!! woohoo!</recent_news>
    <pic>http://profile.ak.facebook.com/object/944/24/s2235780222_35219.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object/944/24/n2235780222_35219.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object/944/24/t2235780222_35219.jpg</pic_small>
    <creator>0</creator>
    <update_time>1170823685</update_time>
    <office/>
    <website/>
    <venue>
      <street/>
    </venue>
    <privacy>OPEN</privacy>
  </group>
  <group>
    <gid>4157941601</gid>
    <name>9/11/07 General Strike</name>
    <nid>0</nid>
    <description>THE NEW GROUP for 2008:
http://www.facebook.com/group.php?gid=9707245966

I am making custom to-do Lists that I can share with my friends, and I thought you might like to try it out. I can assign items to friends, create public and private lists, and set reminders on each to-do item. What kind of Lists do you need to create?

http://apps.facebook.com/listing

Message from new admin:

PLAN FOR THE GENERAL STRIKE OF 2008 AND EVERY YEAR TO COME!

The general strike for 2007 was not very effective, because the general public did not know about it in time, and the strike was planned too late.

Now we have a whole year to spread the word and convince the public to boycott work, travel, buying, etc. on 9/11.

On 9/11 we will protest in the most populated and most busy areas of EVERY city and town we live in all around the world.

Recruit more supporters.

Show up at media events (but don't make us look bad), sports games, etc. where ever we'll be seen by lots of people to let them know.

Get your DVDs ready to pass out.

Get your &quot;9/11 was an inside job&quot;, &quot;investigate 9/11&quot;, etc. signs ready.  

Make your own T-Shirts if you don't want to spend money for 9/11 gear.

Plan, plan, plan and be ready for the next 11th of September.

Got kids?  Keep them at home that day.

SPREAD THE WORD!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

Do whatever you can to let everyone know in time for the next general strike.</description>
    <group_type>Organizations</group_type>
    <group_subtype>Political Organizations</group_subtype>
    <recent_news>*******************************************************
Make collaborative lists directly within Facebook:
http://www.facebook.com/apps/application.php?id=4079843427

*******************************************************
Seriously, try this out -- it's awesome.</recent_news>
    <pic>http://profile.ak.facebook.com/object2/1196/92/s4157941601_400.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object2/1196/92/n4157941601_400.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object2/1196/92/t4157941601_400.jpg</pic_small>
    <creator>693897141</creator>
    <update_time>1218655592</update_time>
    <office/>
    <website>http://apps.facebook.com/listing</website>
    <venue>
      <street/>
    </venue>
    <privacy>OPEN</privacy>
  </group>
  <group>
    <gid>2205007948</gid>
    <name>Facebook Developers</name>
    <nid>0</nid>
    <description/>
    <group_type>Facebook</group_type>
    <group_subtype>Internet &amp; Technology</group_subtype>
    <recent_news/>
    <pic>http://profile.ak.facebook.com/object2/1194/26/s2205007948_5657.jpg</pic>
    <pic_big>http://profile.ak.facebook.com/object2/1194/26/n2205007948_5657.jpg</pic_big>
    <pic_small>http://profile.ak.facebook.com/object2/1194/26/t2205007948_5657.jpg</pic_small>
    <creator>206186</creator>
    <update_time>1182192551</update_time>
    <office/>
    <website>http://developers.facebook.com/</website>
    <venue>
      <street/>
    </venue>
    <privacy>SECRET</privacy>
  </group>
</groups_get_response>
XML;

        $this->mockSendRequest($response);
        $result = $this->instance->get(683226814);
        $this->assertType('SimpleXMLElement', $result);
        foreach ($result as $item) {
            $this->assertObjectHasAttribute('gid', $item);
        }
    }
}

?>
