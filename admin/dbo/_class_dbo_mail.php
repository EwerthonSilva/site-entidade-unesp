<?

/* ================================================================================================================== */
/* DBO CLASS FILE FOR MODULE 'dbo_mail' ========================================= AUTO-CREATED ON 22/05/2016 20:30:31 */
/* ================================================================================================================== */

/* IMPORTANT: This file is generated only in the first DBO sync, what means you should edit only via text editor. */

if(!class_exists('dbo_mail'))
{
	class dbo_mail extends dbo
	{

		var $keyword_blacklist = array(
			'to',
			'subject',
			'body',
			'cc',
			'bcc',
			'from_mail',
			'from_name',
			'reply_to',
			'attachments',
			'smtp_user',
			'smtp_pass',
			'smtp_host',
			'smtp_port',
		);

		/* smart constructor: will perform load() upon numeric argument and loadAll() upon string argument */
		function __construct($foo = '')
		{
			parent::__construct('dbo_mail');
			if($foo != '')
			{
				if(is_numeric($foo))
				{
					$this->id = $foo;
					$this->load();
				}
				elseif(is_string($foo))
				{
					$sql = "SELECT * FROM ".$this->getTable()." WHERE slug = '".$foo."';";
					$this->query($sql);
				}
			}
		}

		//your methods here
		function getBreadcrumbIdentifier()
		{
			return $this->slug;
		}

		function parseKeywords()
		{
			foreach($this as $key => $value)
			{
				if(in_array($key, $this->keyword_blacklist)) continue;
				foreach($this->__pocket as $key => $value)
				{
					$this->dbo_mail_subject = str_replace('[['.$key.']]', nl2br($value), $this->dbo_mail_subject);
					$this->dbo_mail_body = str_replace('[['.$key.']]', nl2br($value), $this->dbo_mail_body);
				}
				foreach($this->__data as $key => $value)
				{
					$this->dbo_mail_subject = str_replace('[['.$key.']]', nl2br($value), $this->dbo_mail_subject);
					$this->dbo_mail_body = str_replace('[['.$key.']]', nl2br($value), $this->dbo_mail_body);
				}
			}
		}

		function smartSet($array)
		{
			foreach((array)$array as $key => $value)
			{
				$this->{$key} = $value;
			}
		}

		function preview()
		{
			$this->parseKeywords();
			ob_start();
			?>
			<p><strong>To: <?= implode(', ', (array)$this->to) ?></strong></p>
			<p><strong>Cc: <?= implode(', ', (array)$this->cc) ?></strong></p>
			<p><strong>Bcc: <?= implode(', ', (array)$this->bcc) ?></strong></p>
			<p><strong>Assunto:</strong> <?= $this->dbo_mail_subject ?></p>
			<p><strong>Mensagem:</strong></p>
			<?= $this->dbo_mail_body; ?>
			<?php
			return ob_get_clean();
		}

		function send()
		{
			$this->parseKeywords();
			return dboMail(array(
				'to' => $this->to,
				'subject' => $this->dbo_mail_subject,
				'body' => $this->dbo_mail_body,
				'cc' => $this->cc,
				'bcc' => $this->bcc,
				'from_mail' => $this->from_mail,
				'from_name' => $this->from_name,
				'reply_to' => $this->reply_to,
				'attachments' => $this->attachments,
				'smtp_user' => $this->smpt_user,
				'smtp_pass' => $this->pass,
				'smtp_host' => $this->smtp_host,
				'smtp_port' => $this->smtp_port,
			));
		}

	} //class declaration
} //if ! class exists

?>