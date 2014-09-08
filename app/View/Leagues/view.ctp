<div class="leagues view">
<h2><?php echo __('League'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($league['League']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($league['League']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Description'); ?></dt>
		<dd>
			<?php echo h($league['League']['description']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Center'); ?></dt>
		<dd>
			<?php echo $this->Html->link($league['Center']['name'], array('controller' => 'centers', 'action' => 'view', $league['Center']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit League'), array('action' => 'edit', $league['League']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete League'), array('action' => 'delete', $league['League']['id']), array(), __('Are you sure you want to delete # %s?', $league['League']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Leagues'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New League'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Centers'), array('controller' => 'centers', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Center'), array('controller' => 'centers', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Games'), array('controller' => 'games', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Game'), array('controller' => 'games', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Teams'), array('controller' => 'teams', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Team'), array('controller' => 'teams', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Games'); ?></h3>
	<?php if (!empty($league['Game'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Game Name'); ?></th>
		<th><?php echo __('Game Description'); ?></th>
		<th><?php echo __('Game Datetime'); ?></th>
		<th><?php echo __('Red Team Name'); ?></th>
		<th><?php echo __('Green Team Name'); ?></th>
		<th><?php echo __('Red Score'); ?></th>
		<th><?php echo __('Green Score'); ?></th>
		<th><?php echo __('Red Adj'); ?></th>
		<th><?php echo __('Green Adj'); ?></th>
		<th><?php echo __('Winner'); ?></th>
		<th><?php echo __('Red Eliminated'); ?></th>
		<th><?php echo __('Green Eliminated'); ?></th>
		<th><?php echo __('League Round'); ?></th>
		<th><?php echo __('League Match'); ?></th>
		<th><?php echo __('League Game'); ?></th>
		<th><?php echo __('Pdf Id'); ?></th>
		<th><?php echo __('Center Id'); ?></th>
		<th><?php echo __('League Id'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($league['Game'] as $game): ?>
		<tr>
			<td><?php echo $game['id']; ?></td>
			<td><?php echo $game['game_name']; ?></td>
			<td><?php echo $game['game_description']; ?></td>
			<td><?php echo $game['game_datetime']; ?></td>
			<td><?php echo $game['red_team_name']; ?></td>
			<td><?php echo $game['green_team_name']; ?></td>
			<td><?php echo $game['red_score']; ?></td>
			<td><?php echo $game['green_score']; ?></td>
			<td><?php echo $game['red_adj']; ?></td>
			<td><?php echo $game['green_adj']; ?></td>
			<td><?php echo $game['winner']; ?></td>
			<td><?php echo $game['red_eliminated']; ?></td>
			<td><?php echo $game['green_eliminated']; ?></td>
			<td><?php echo $game['league_round']; ?></td>
			<td><?php echo $game['league_match']; ?></td>
			<td><?php echo $game['league_game']; ?></td>
			<td><?php echo $game['pdf_id']; ?></td>
			<td><?php echo $game['center_id']; ?></td>
			<td><?php echo $game['league_id']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'games', 'action' => 'view', $game['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'games', 'action' => 'edit', $game['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'games', 'action' => 'delete', $game['id']), array(), __('Are you sure you want to delete # %s?', $game['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Game'), array('controller' => 'games', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Teams'); ?></h3>
	<?php if (!empty($league['Team'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Name'); ?></th>
		<th><?php echo __('Points'); ?></th>
		<th><?php echo __('League Id'); ?></th>
		<th><?php echo __('Captain Id'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($league['Team'] as $team): ?>
		<tr>
			<td><?php echo $team['id']; ?></td>
			<td><?php echo $team['name']; ?></td>
			<td><?php echo $team['points']; ?></td>
			<td><?php echo $team['league_id']; ?></td>
			<td><?php echo $team['captain_id']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'teams', 'action' => 'view', $team['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'teams', 'action' => 'edit', $team['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'teams', 'action' => 'delete', $team['id']), array(), __('Are you sure you want to delete # %s?', $team['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Team'), array('controller' => 'teams', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
